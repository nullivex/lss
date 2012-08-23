<?php

//this class handles the package db, constructing, connecting, querying

class PkgDb {

	static $db = false;
	static $inst = false;	
	
	public static function _get(){
		if(!self::$inst) self::$inst = new PkgDb();
		return self::$inst;
	}
	
	private function __construct(){
		self::$db = $this->connectDb();
	}
	
	private function connectDb(){
		return new PDO(
			'sqlite:'.ROOT.'/pkg/pkg.db', //dsn
			null, //user
			null, //pass
			array(
				PDO::ATTR_ERRMODE		=>	PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_PERSISTENT	=>	true
			)
		);
	}
	
	public function clearDb(){
		self::$db->prepare('drop table if exists pkg')->execute();
		self::$db->prepare('drop table if exists pkg_dep')->execute();
		self::$db->prepare('drop table if exists pkg_dep_ver')->execute();
		self::$db->prepare('drop table if exists pkg_manifest')->execute();
		return true;
	}
	
	public function initDb(){
		self::$db->prepare(DB_TBL_PKG)->execute();
		self::$db->prepare(DB_TBL_PKG_DEP)->execute();
		self::$db->prepare(DB_TBL_PKG_DEP_VER)->execute();
		self::$db->prepare(DB_TBL_PKG_MANIFEST)->execute();
		return true;
	}
	
	public function addToDb(PkgDef $pkg){
		//insert initial package
		$query = self::$db->prepare('
			INSERT INTO pkg (
				fqn,
				sqn,
				name,
				class,
				repo,
				version,
				version_int
			) VALUES (?,?,?,?,?,?,?)
		');
		$query->execute(array(
			gfa($pkg->data,'info','fqn'),
			gfa($pkg->data,'info','sqn'),
			gfa($pkg->data,'info','name'),
			gfa($pkg->data,'info','class'),
			gfa($pkg->data,'info','repo'),
			gfa($pkg->data,'info','version'),
			ip2long(gfa($pkg->data,'info','version'))
		));
		$pkg_id = self::$db->lastInsertId();
		//insert package deps
		foreach(gfa($pkg->data,'dep') as $dep_fqn => $dep){
			$query = self::$db->prepare('
				INSERT INTO pkg_dep (
					pkg_id,
					fqn
				) VALUES (?,?)
			');
			$query->execute(array(
				$pkg_id,
				$dep_fqn
			));
			$pkg_dep_id = self::$db->lastInsertId('pkg_dep_id');
			//insert dep versions
			foreach(gfa($dep,'versions') as $version){
				$query = self::$db->prepare('
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
					ip2long($version)
				));
			}	
		}
		//insert the manifest
		foreach(gfa($pkg->data,'manifest') as $manifest){
			$query = self::$db->prepare('
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
var_dump($def);
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
				// TODO: sanity check more things here?
				if($skip === true){
					unset($def);
					continue;
				}

				// TODO: database magic
				$pkg_id = $this->addToDb($def);
				unset($def);
			}
		}
	}
	
	public function show(){
		$out = '';
		$repo = null;
		//grab all our data then show it
		$query = self::$db->prepare('SELECT ROWID,* FROM pkg ORDER BY fqn ASC');
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
			//get the deps
			$qa = self::$db->prepare('SELECT ROWID,* FROM pkg_dep WHERE pkg_id = ? ORDER BY fqn ASC');
			$qa->execute(array($pkg['rowid']));
			foreach($qa->fetchAll() as $pkg_dep){
				$out .= "    [DEP ".$pkg_dep['fqn']."]\n";
				$qb = self::$db->prepare('SELECT ROWID,* FROM pkg_dep_ver WHERE pkg_dep_id = ? ORDER BY version_int ASC');
				$qb->execute(array($pkg_dep['rowid']));
				foreach($qb->fetchAll() as $pkg_dep_ver)
					$out .= "      [DEP VER ".$pkg_dep_ver['version']."]\n";
			}
			//get the manifest
			$out .= "    [MANIFEST]\n";
			$qa = self::$db->prepare('SELECT * FROM pkg_manifest WHERE pkg_id = ? ORDER BY file ASC');
			$qa->execute(array($pkg['rowid']));
			foreach($qa->fetchAll() as $pkg_manifest)
				$out .= "      [FILE ".$pkg_manifest['file']."]\n";
		}
		return $out;
	}
}

//some of the database structs
define('DB_TBL_PKG',
<<<SQL
	CREATE TABLE 'pkg' (
		'pkg_id' INT PRIMARY KEY ,
		'fqn' VARCHAR ( 62 ) NOT NULL ,
		'sqn' VARCHAR ( 41 ) NOT NULL ,
		'name' VARCHAR ( 20 ) NOT NULL ,
		'class' VARCHAR ( 20 ) NOT NULL DEFAULT 'misc' ,
		'repo' VARCHAR ( 20 ) NOT NULL DEFAULT 'main' ,
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
		'fqn' VARCHAR ( 62 ) NOT NULL 
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