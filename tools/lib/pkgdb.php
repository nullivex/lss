<?php

//this class handles the package db, constructing, connecting, querying

class PkgDb {

	public $db = false;

	public static function _get($dbfile=null){
		if(is_null($dbfile)) $dbfile = CACHE.'/pkg.db';
		return new PkgDb($dbfile);
	}

	private function __construct($dbfile){
		$this->db = $this->connectDb($dbfile);
	}

	private function connectDb($dbfile){
		return new PDO(
			'sqlite:'.$dbfile, //dsn
			null, //user
			null, //pass
			array(
				PDO::ATTR_ERRMODE		=>	PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_PERSISTENT	=>	true
			)
		);
	}

	//this function originally copied a built database from our mirrors
	//however, now we need to get a built database from multiple mirrors and merge them
	public static function update(){
		require_once(ROOT.'/tools/lib/pkg.php');
		$tmp = CACHE.'/tmp.pkg.db';
		//get db handle
		$db = PkgDb::_get();
		//re-init db
		$db->clearDb();
		$db->initDb();
		//get db from each mirror
		$mirrors = array_reverse(getFromDefMerged('mirror'));
		if(!is_array($mirrors) || !count($mirrors)) throw new Exception('No mirrors available for update');
		foreach($mirrors as $mirror){
			//get the remote db
			$src = $mirror.'/pkg.db';
			$buff = Pkg::getFromMirror($mirror,$src,$mirrorauth);
			//if we have a bad value continue on
			if($buff === false) continue;
			//write the new db
			$rv = file_put_contents($tmp,$buff); unset($buff);
			if($rv === false) throw new Exception('Failed to write tmp database: '.$tmp);
			//setup the second db handle
			$srcdb = new PkgDb($tmp);
			foreach($srcdb->pkgList() as $pkg){
				try {
					$def = self::makePkg($srcdb,$pkg);
					$db->addToDb($def,$mirror,$mirrorauth);
				} catch(Exception $e){
					if($e->getCode() == ERR_PKG_EXISTS){
						UI::out('Ignored '.$pkg['fqn'].' from '.$mirror.' it was already defined.'."\n");
					} else throw $e;
				}
			}
		}
		//cleanup
		unset($db,$srcdb);
		unlink($tmp);
		return true;
	}

	public static function makePkg($db,$pkg){
		$arr = array('info'=>array(),'manifest'=>array(),'dep'=>array());
		//set basic info
		$arr['info']['fqn'] = $pkg['fqn'];
		$arr['info']['sqn'] = $pkg['sqn'];
		$arr['info']['repo'] = $pkg['repo'];
		$arr['info']['class'] = $pkg['class'];
		$arr['info']['name'] = $pkg['name'];
		$arr['info']['version'] = $pkg['version'];
		$arr['info']['description'] = $pkg['description'];
		//deal with manifest
		foreach($db->getManifest($pkg['rowid']) as $manifest)
			$arr['manifest'][] = $manifest['file'];
		//deal with deps
		foreach($db->getDeps($pkg['rowid']) as $dep){
			$arr['dep'][$dep['fqn']] = array('versions'=>array());
			foreach($db->getDepVers($dep['rowid']) as $dep_ver)
				$arr['dep'][$dep['fqn']]['versions'][] = $dep_ver['version'];
		}
		$obj = new PkgDef($pkg['sqn'],$pkg['repo'],PkgDef::READONLY,false);
		$obj->data = $arr;
		return $obj;
	}

	public function clearDb(){
		$this->db->prepare('drop table if exists pkg')->execute();
		$this->db->prepare('drop table if exists pkg_dep')->execute();
		$this->db->prepare('drop table if exists pkg_dep_ver')->execute();
		$this->db->prepare('drop table if exists pkg_manifest')->execute();
		return true;
	}

	public function initDb(){
		$this->db->prepare(DB_TBL_PKG)->execute();
		$this->db->prepare(DB_TBL_PKG_DEP)->execute();
		$this->db->prepare(DB_TBL_PKG_DEP_VER)->execute();
		$this->db->prepare(DB_TBL_PKG_MANIFEST)->execute();
		return true;
	}

	public function addToDb(PkgDef $pkg,$mirror=null,$mirrorauth=null){
		try {
			$this->getByFQN($pkg->data['info']['fqn']);
			throw new Exception('Package already exists in database.',ERR_PKG_EXISTS);
		} catch(Exception $e){
			if($e->getCode() !== ERR_PKG_NOT_FOUND) throw $e;
		}
		//insert initial package
		$query = $this->db->prepare('
			INSERT INTO pkg (
				mirror,
				mirrorauth,
				fqn,
				sqn,
				name,
				class,
				repo,
				description,
				version,
				version_int
			) VALUES (?,?,?,?,?,?,?,?,?,?)
		');
		$query->execute(array(
			$mirror,
			$mirrorauth,
			gfa($pkg->data,'info','fqn'),
			gfa($pkg->data,'info','sqn'),
			gfa($pkg->data,'info','name'),
			gfa($pkg->data,'info','class'),
			gfa($pkg->data,'info','repo'),
			gfa($pkg->data,'info','description') ? gfa($pkg->data,'info','description') : DEFAULT_DESCRIPTION,
			gfa($pkg->data,'info','version'),
			Pkg::v2i(gfa($pkg->data,'info','version'))
		));
		$pkg_id = $this->db->lastInsertId();
		//insert package deps
		foreach(gfa($pkg->data,'dep') as $dep_fqn => $dep){
			$query = $this->db->prepare('
				INSERT INTO pkg_dep (
					pkg_id,
					fqn
				) VALUES (?,?)
			');
			$query->execute(array(
				$pkg_id,
				$dep_fqn
			));
			$pkg_dep_id = $this->db->lastInsertId('pkg_dep_id');
			//insert dep versions
			foreach(gfa($dep,'versions') as $version){
				$query = $this->db->prepare('
					INSERT INTO pkg_dep_ver (
						pkg_dep_id,
						pkg_id,
						version,
						version_int
					) VALUES (?,?,?,?)
				');
				$query->execute(array(
					$pkg_dep_id,
					$pkg_id,
					$version,
					Pkg::v2i($version)
				));
			}
		}
		//insert the manifest
		foreach(gfa($pkg->data,'manifest') as $manifest){
			$query = $this->db->prepare('
				INSERT INTO pkg_manifest (
					pkg_id,
					file
				) VALUES (?,?)
			');
			$query->execute(array(
				$pkg_id,
				$manifest
			));
		}
		return $pkg_id;
	}

	public function build($cleardb=true){
		//clear the database
		if($cleardb){
			$this->clearDb();
			$this->initDb();
		}
		//now we traverse the package def folder and build a list of pkgs
		$defs = array();
		$dirs = array();
		$fulldefpath = ROOT.'/'.DEF_PATH.'/';
		$dir = $fulldefpath;
		while(is_dir($dir)){
			if($dh = opendir($dir)){
				while(($file = readdir($dh)) !== false){
					if(filetype($dir . $file) == 'dir'){
						if(($file != '.') && ($file != '..'))
							$dirs[] = $dir.$file.'/';
					} else {
						if(preg_match('|^'.$fulldefpath.'([^/]+)/([^/]+)/([^/]+).lss$|',$dir.$file,$m)){
							list(,$repo,$class,$name) = $m;
							if(!array_key_exists($repo,$defs))
								$defs[$repo] = array();
							$defs[$repo][] = $class.'/'.$name;
						}
					}
				}
				closedir($dh);
			}
			$dir = array_pop($dirs);
		}
		// sort stuff for predictability
		foreach(array_keys($defs) as $repo)
			sort($defs[$repo]);
		//once we have that we will insert them into the database
		foreach(array_keys($defs) as $repo){
			printf("[REPO %s]\n",$repo);
			foreach($defs[$repo] as $pkg){
				printf(" [PKG %s]\n",$pkg);
				$def = new pkgDef($pkg,$repo,pkgDef::READONLY);
				$skip = false;
				if($pkg != $def->data['info']['class'].'/'.$def->data['info']['name']){
					printf("WARNING: Package '%s' claims to be '%s/%s' in file '%s', skipping...\n"
						  ,$pkg
						  ,$def->data['info']['class']
						  ,$def->data['info']['name']
						  ,$def->filename
					);
					$skip = true;
				}
				if($skip === true){
					unset($def);
					continue;
				}
				$pkg_id = $this->addToDb($def);
				unset($def);
			}
		}
	}

	public static function export($src,$dest){
		//grab db file
		$buff = file_get_contents($src);
		file_put_contents($dest,$buff);
		return true;
	}

	public function show(){
		$out = '';
		$repo = null;
		//grab all our data then show it
		$query = $this->db->prepare('SELECT ROWID,* FROM pkg ORDER BY fqn ASC');
		$query->execute();
		foreach($query->fetchAll() as $pkg){
			//print repo stuff if we need to
			if($repo != $pkg['repo']){
				$repo = $pkg['repo'];
				$out .= "\n[REPO $repo]\n";
			}
			//package name and details
			$out .= "  [PKG ".$pkg['sqn']."]\n";
			$out .= "    [VERSION: ".$pkg['version']."]\n";
			$out .= "    [MIRROR: ".$pkg['mirror'].(($pkg['mirrorauth'])?'[+AUTH]':'')."]\n";
			//get the deps
			$qa = $this->db->prepare('SELECT ROWID,* FROM pkg_dep WHERE pkg_id = ? ORDER BY fqn ASC');
			$qa->execute(array($pkg['rowid']));
			foreach($qa->fetchAll() as $pkg_dep){
				$out .= "    [DEP ".$pkg_dep['fqn']."]\n";
				$qb = $this->db->prepare('SELECT ROWID,* FROM pkg_dep_ver WHERE pkg_dep_id = ? ORDER BY version_int ASC');
				$qb->execute(array($pkg_dep['rowid']));
				foreach($qb->fetchAll() as $pkg_dep_ver)
					$out .= "      [DEP VER ".$pkg_dep_ver['version']."]\n";
			}
			//get the manifest
			$out .= "    [MANIFEST]\n";
			$qa = $this->db->prepare('SELECT * FROM pkg_manifest WHERE pkg_id = ? ORDER BY file ASC');
			$qa->execute(array($pkg['rowid']));
			foreach($qa->fetchAll() as $pkg_manifest)
				$out .= "      [FILE ".$pkg_manifest['file']."]\n";
		}
		return $out;
	}

	public function pkgList(){
		$query = $this->db->prepare('SELECT ROWID,* FROM pkg ORDER BY fqn ASC');
		$query->execute();
		return $query->fetchAll();
	}

	public function search($keywords){
		$query = $this->db->prepare('SELECT * FROM pkg WHERE fqn LIKE ? ORDER BY fqn ASC');
		$query->execute(array('%'.$keywords.'%'));
		$out = '';
		foreach($query->fetchAll() as $pkg){
			$out .= '  '.$pkg['fqn']."\t\t-\t".$pkg['description']."\n";
		}
		return $out;
	}

	public function find($qn){
		foreach(array('name','sqn','fqn') as $key){
			try {
				return $this->_find($qn,$key);
			} catch(Exception $e){
				if($e->getCode() == 3) continue;
				else throw $e;
			}
		}
		throw new Exception('Could not find package matching: "'.$qn .'" maybe you meant one of these?',2);
	}

	private function _find($qn,$key){
		//try by key
		$query = $this->db->prepare('SELECT rowid,* FROM pkg WHERE '.$key.' = ?');
		$query->execute(array($qn));
		$result = $query->fetchAll(); unset($query);
		if(count($result) == 1){
			return array_shift($result);
		} elseif(count($result) > 1){
			throw new Exception('Multiple packages match: '.$qn.' please be more specific',1);
		}
		throw new Exception('no results',3);
	}

	public function getDeps($pkg_id){
		$query = $this->db->prepare('SELECT rowid,* FROM pkg_dep WHERE pkg_id = ? ORDER BY fqn ASC');
		$query->execute(array($pkg_id));
		return $query->fetchAll();
	}

	public function getDepVers($pkg_dep_id){
		$query = $this->db->prepare('SELECT rowid,* FROM pkg_dep_ver WHERE pkg_dep_id = ? ORDER BY version_int ASC');
		$query->execute(array($pkg_dep_id));
		return $query->fetchAll();
	}

	public function getManifest($pkg_id){
		$query = $this->db->prepare('SELECT rowid,* FROM pkg_manifest WHERE pkg_id = ? ORDER BY file ASC');
		$query->execute(array($pkg_id));
		return $query->fetchAll();
	}

	public function getByFQN($fqn){
		$query = $this->db->prepare('SELECT rowid,* FROM pkg WHERE fqn = ? LIMIT 1');
		$query->execute(array($fqn));
		$result = $query->fetch(); $query->closeCursor();
		if(!$result) throw new Exception('Could not find package by FQN: '.$fqn,ERR_PKG_NOT_FOUND);
		return $result;
	}

}

//some of the database structs
define('DB_TBL_PKG',
<<<SQL
	CREATE TABLE 'pkg' (
		'pkg_id' INT PRIMARY KEY ,
		'mirror' VARCHAR ( 255 ) NULL,
		'mirrorauth' VARCHAR ( 255 ) NULL,
		'fqn' VARCHAR ( 62 ) NOT NULL ,
		'sqn' VARCHAR ( 41 ) NOT NULL ,
		'name' VARCHAR ( 20 ) NOT NULL ,
		'class' VARCHAR ( 20 ) NOT NULL DEFAULT 'misc' ,
		'repo' VARCHAR ( 20 ) NOT NULL DEFAULT 'main' ,
		'description' VARCHAR (100) NULL DEFAULT 'An OpenLSS Package',
		'version' VARCHAR ( 16 ) NOT NULL DEFAULT '0.0' ,
		'version_int' INT NOT NULL
	)
SQL
);

define('DB_TBL_PKG_DEP',
<<<SQL
	CREATE TABLE 'pkg_dep' (
		'pkg_dep_id' INT PRIMARY KEY ,
		'pkg_id' INT NOT NULL ,
		'fqn' VARCHAR ( 62 ) NOT NULL ,
		'pre' INT NOT NULL DEFAULT '0' 
	)
SQL
);

define('DB_TBL_PKG_DEP_VER',
<<<SQL
	CREATE TABLE 'pkg_dep_ver' (
		'pkg_dep_ver_id' INT PRIMARY KEY ,
		'pkg_dep_id' INT NOT NULL ,
		'pkg_id' INT NOT NULL ,
		'version' VARCHAR ( 16 ) NOT NULL ,
		'version_int' INT NOT NULL
	)
SQL
);

define('DB_TBL_PKG_MANIFEST',
<<<SQL
	CREATE TABLE 'pkg_manifest' (
		'pkg_manifest_id' INT PRIMARY KEY ,
		'pkg_id' INT NOT NULL ,
		'file' VARCHAR ( 255 ) NOT NULL
	)
SQL
);