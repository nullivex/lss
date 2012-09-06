<?php

//--------------------------
//Local Target Functions
//--------------------------
function listInstalled(){
	$tgtdef = TgtDef::_get();
	UI::out('Currently installed packages at '.UsrDef::_get()->get('target')."\n\n");
	UI::out('  PACKAGE'.str_repeat(' ',65).'VERSION'."\n");
	foreach($tgtdef->get('installed') as $pkg => $version)
		UI::out("  $pkg".str_repeat(' ',72 - strlen($pkg))."$version\n");
	UI::out("\n");
	return;
}

//--------------------------
//Database Functions
//--------------------------
function update(){
	PkgDb::update();
	UI::out("Package database updated.\n");
}

function buildDb(){
	PkgDb::_get(ROOT.'/pkg/pkg.db')->build();
	UI::out("Package database has been built.\n");
}

function exportDb($mirror){
	if(is_null($mirror)) throw new Exception('Mirror must be provided for DB export');
	PkgDb::export(ROOT.'/pkg/pkg.db',$mirror.'/pkg.db');
	UI::out("Package database has been exported.\n");
}

function showDb($opts){
	if(mda_get($opts,'db-file')) $dbfile = mda_get($opts,'db-file');
	else $dbfile = null;
	UI::out(PkgDb::_get($dbfile)->show());
	UI::out("Database display complete.\n");
}


//--------------------------
//Package Functions
//--------------------------
function search($keywords){
	UI::out("Searching for packages matching \"$keywords\"\n");
	UI::out(PkgDb::_get()->search($keywords));
}

function upgrade(){
	UI::out("Upgrading ".UsrDef::_get()->get('target')."\n");
	//grab def handle
	$tgtdef = TgtDef::_get();
	//grab the db handle
	$db = PkgDb::_get();
	//look for packages that need upgraded
	$pkgs = array();
	foreach($tgtdef->get('installed') as $pkg => $version){
		//grab the remote package and check the version
		$rpkg = $db->getByFQN($pkg);
		if($rpkg['version_int'] > Pkg::v2i($version)){
			UI::out("  Selecting $pkg for upgrade from $version to ".$rpkg['version']."\n");
			$pkgs[] = $rpkg;
		}
	}
	//compile install string
	$pkg_str = '';
	foreach($pkgs as $pkg) $pkg_str .= $pkg['fqn'].',';
	//see if we have packages to upgrade
	if(empty($pkg_str)){
		UI::out("Already up-to-date\n");
		exit;
	}
	//chain to install as it is the same process
	install($tgtdef,trim($pkg_str,','),true);
	
	//connect to the db for any db hooks
	if(in_array(PACKAGE_DB_FQN,array_keys($tgtdef->get('installed'))) || Pkg::isSelected(PACKAGE_DB_FQN,$pkgs)){
		Db::_get()->setConfig(Db::targetDbConfig(TARGET))->connect();
	}

	//now we have to manually do the upgrade hooks
	UI::out("Upgrade packages.\n");
	foreach($pkgs as $pkg){
		$start_version = Pkg::v2i($tgtdef->get('installed',$pkg['fqn']));
		$end_version = $pkg['version_int'];
		$hook_file = Pkg::hookFile($pkg['fqn']);
		UI::out('  Upgrading '.$pkg['fqn']);
		for($i=++$start_version;$i<=$end_version;$i++){
			$hook_file = Pkg::hookFile($pkg['fqn']);
			if(file_exists($hook_file)){
				exec_hook($hook_file,'upgrade_'.$i);
			}
		}
		UI::out("... done\n");
	}

	UI::out("Updating local package database...");
	foreach($pkgs as $pkg)
		$tgtdef->data['installed'][$pkg['fqn']] = $pkg['version'];
	UI::out(" done\n");

	UI::out("Upgrade complete.\n");
}

function install($packages,$upgrade=false){
	$tgtdef = TgtDef::_get();
	//create a backup
	if(getFromDef('no_backup')) backup();
	//get db
	$db = PkgDb::_get();
	//blow up packages and find them
	$pkgs = array();
	$ui = UI::_get();
	$ui->out("Locating packages\n");
	foreach(explode(',',$packages) as $pkg_qn){
		//see if we can find the package
		try {
			$pkgs[] = $pkg = $db->find($pkg_qn);
		} catch(Exception $e){
			if($e->getCode() == 1 || $e->getCode() == 2){
				$ui->out($e->getMessage()."\n");
				$ui->out($db->search($pkg_qn));
				return false;
			} else throw $e;
		}
	}

	//now lets do some depsolving
	$ui->out("Selecting dependencies if needed\n");
	foreach($pkgs as $pkg){
		foreach($db->getDeps($pkg['rowid']) as $dep){
			$arr = $db->getByFQN($dep['fqn']);
			if($dep['pre']) $arr['pre'] = true;
			$pkgs[] = $arr;
		}
	}

	//remove duplicates and print install list
	$ui->out("Unselecting duplicates\n");
	$tmp_pkgs = array();
	foreach($pkgs as $key => $pkg) $tmp_pkgs[$key] = $pkg['fqn'];
	remove_dups($tmp_pkgs);
	foreach($pkgs as $key => $pkg){
		if(!in_array($key,array_keys($tmp_pkgs))) unset($pkgs[$key]);
	}

	//remove locally installed packages
	$ui->out("Unselecting locally installed packages\n");
	foreach($pkgs as $key => $pkg)
		if(array_key_exists($pkg['fqn'],$tgtdef->data['installed']) && $tgtdef->data['installed'][$pkg['fqn']] == $pkg['version']) unset($pkgs[$key]);

	//make sure we still have pkgs
	if(!count($pkgs)){
		$ui->out("No packages to be installed.\n",true);
		exit;
	}

	//review the install action
	$ui->out("The following packages will be ".($upgrade === true ? 'UPGRADED' : 'INSTALLED').":\n");
	foreach($pkgs as $pkg) $ui->out('  '.$pkg['fqn']."\n");

	if(!defined('ANSWER_YES')){
		if(!$ui->ask("Are you sure you want to continue?",array('y','n'),false)) exit;
	}
	$ui->out("Starting process\n");

	//set tgtdef writable
	$tgtdef->iostate = TgtDef::READWRITE;

	//retrieve packages
	$ui->out("Downloading packages from mirror\n");
	foreach($pkgs as $key => $pkg){
		$ui->out('  Downloading '.$pkg['fqn']);
		$dest = $pkgs[$key]['file'] = CACHE.'/mirror/'.$pkg['fqn'].'.tar.bz2';
		$src = $pkg['mirror'].'/'.$pkg['fqn'].'.tar.bz2';
		@mkdir(dirname($dest),0755,true);
		//download package
		$buff = Pkg::getFromMirror(
			$src, //url to download from
			'Could not download package: '.$src //err message pre-text
		);
		if($buff === false) continue;
		//write package
		$rv = @file_put_contents($dest,$buff);
		if(!$rv) throw new Exception('Failed to save package: '.$dest);
		$ui->out("... done\n");
	}

	//extract packages
	$ui->out("Extracting packages to target\n");
	foreach($pkgs as $pkg){
		$ui->out('  Extracting '.$pkg['fqn']);
		Pkg::extract($pkg['file'],TARGET);
		$ui->out("... done\n");
	}

	//process hooks
	if($upgrade === false){
		$ui->out("Setting up new packages\n");
		//pass 1 pre-depends
		foreach($pkgs as $pkg){
			if(isset($pkg['pre']) && $pkg['pre'] === false) continue;
			else if(!isset($pkg['pre'])) continue;
			$ui->out('  Setting up '.$pkg['fqn']);
			$hook_file = Pkg::hookFile($pkg['fqn']);
			if(file_exists($hook_file)) exec_hook($hook_file,'install');
			$ui->out("... done\n");
		}
		
		//connect to the db for any db hooks
		if(in_array(PACKAGE_DB_FQN,array_keys($tgtdef->get('installed'))) || Pkg::isSelected(PACKAGE_DB_FQN,$pkgs)){
			Db::_get()->setConfig(Db::targetDbConfig(TARGET))->connect();
		}
		
		foreach($pkgs as $pkg){
			if(isset($pkg['pre']) && $pkg['pre'] === true) continue;
			$ui->out('  Setting up '.$pkg['fqn']);
			$hook_file = Pkg::hookFile($pkg['fqn']);
			if(file_exists($hook_file)) exec_hook($hook_file,'install');
			$ui->out("... done\n");
		}
	}

	//update the locally installed packages
	if($upgrade === false){
		$ui->out("Updating local package database...");
		foreach($pkgs as $pkg)
			$tgtdef->data['installed'][$pkg['fqn']] = $pkg['version'];
		$ui->out(" done\n");
	}

	//done
	$ui->out("Install complete.\n");

}

function remove($packages,$purge=null){
	$tgtdef = TgtDef::_get();
	//create a backup
	if(getFromDef('no_backup')) backup();
	//grab ui handle
	$ui = UI::_get();
	//are we purging or removing?
	if($purge === true) $ui->out("Purging $packages\n");
	else $ui->out("Remove $packages\n");

	//get db
	$db = PkgDb::_get();

	//blow up packages and find them
	$pkgs = array();
	$ui->out("Locating packages\n");
	foreach(explode(',',$packages) as $pkg_qn){
		//see if we can find the package
		try {
			$pkgs[] = $pkg = $db->find($pkg_qn);
		} catch(Exception $e){
			if($e->getCode() == 1 || $e->getCode() == 2){
				$ui->out($e->getMessage()."\n");
				$ui->out($db->search($pkg_qn));
				return false;
			} else throw $e;
		}
	}

	//figure out which packages are actually install
	foreach($pkgs as $key => $pkg)
		if(!isset($tgtdef->data['installed'][$pkg['fqn']])) unset($pkgs[$key]);

	//make sure packages arent depended on
	$tmp_pkgs = array();
	foreach($pkgs as $key => $pkg) $tmp_pkgs[$key] = $pkg['fqn'];
	foreach(array_keys($tgtdef->data['installed']) as $fqn){
		$pkg = $db->getByFQN($fqn);
		foreach($db->getDeps($pkg['rowid']) as $dep){
			$key = array_search($dep['fqn'],$tmp_pkgs);
			if($key !== false && !in_array($pkg['fqn'],$tmp_pkgs) && isset($pkgs[$key])){
				$ui->out('  Unselected '.$pkgs[$key]['fqn'].' it is required by '.$pkg['fqn']."\n");
				unset($pkgs[$key]);
			}
		}
	}

	//make sure we still have packages to process
	if(!count($pkgs)){
		$ui->out("No packages to be removed.\n",true);
		exit;
	}

	//review the install action
	$ui->out("The following packages will be ".($purge === true ? 'PURGED' : 'REMOVED').":\n");
	foreach($pkgs as $pkg) $ui->out('  '.$pkg['fqn']."\n");

	if(!defined('ANSWER_YES')){
		if(!$ui->ask("Are you sure you want to continue?",array('y','n'),false)) exit;
	}

	//set tgtdef writable
	$tgtdef->iostate = TgtDef::READWRITE;
	
	//connect to the db for any db hooks
	if(in_array(PACKAGE_DB_FQN,array_keys($tgtdef->get('installed'))) || Pkg::isSelected(PACKAGE_DB_FQN,$pkgs)){
		Db::_get()->setConfig(Db::targetDbConfig(TARGET))->connect();
	}

	//process hooks
	$ui->out("Processing removals\n");
	foreach($pkgs as $pkg){
		$ui->out('  Cleaning up '.$pkg['fqn']);
		$hook_file = Pkg::hookFile($pkg['fqn']);
		if(file_exists($hook_file)){
			exec_hook($hook_file,'remove');
		}
		$ui->out("... done\n");
	}

	//process removal hooks only in a purge situation
	if($purge){
		//process hooks
		$ui->out("Processing purges\n");
		foreach($pkgs as $pkg){
			$ui->out('  Purging '.$pkg['fqn']);
			$hook_file = Pkg::hookFile($pkg['fqn']);
			if(file_exists($hook_file)){
				exec_hook($hook_file,'purge');
			}
			$ui->out("... done\n");
		}
	}

	//remove files
	$ui->out("Starting removal\n");
	foreach($pkgs as $pkg){
		$ui->out('  Removing package '.$pkg['fqn']."\n");
		foreach($db->getManifest($pkg['rowid']) as $manifest){
			$file = TARGET.'/'.$manifest['file'];
			$ui->out('    Removing file '.$file."\n");
			@unlink($file);
			@unlink(dirname($file));
		}
	}

	//update the locally installed packages
	$ui->out("Updating local package database...");
	foreach($pkgs as $pkg)
		unset($tgtdef->data['installed'][$pkg['fqn']]);
	$ui->out(" done\n");

	//done
	$ui->out("Install complete.\n");
}

function backup($name=null,$db_dump=null){
	//deal with name
	$tgtdef = TgtDef::_get();
	if(is_null($name) || $name === false) $name = time();
	else $name = urlname($name);
	UI::out("Starting backup to \"$name\"\n");

	//figure out some files
	$file_db_backup = TARGET.'/.sql.bak';
	$file_tmp_backup = '/tmp/'.$name.'.tar';
	$file_backup = TARGET.'/.bak/'.$name.'.tar.bz2';

	//Back up database
	$db_dump = !is_null($db_dump) ? $db_dump : $tgtdef->get('db-dump');
	if($db_dump){
		UI::out("Backing up database\n");
		run($db_dump.' > '.$file_db_backup);
	} else {
		UI::out("WARNING: No database dump command specified, NOT backing up database\n");
	}

	//create an archive of the files
	UI::out("Backing up files\n");
	run('cd '.TARGET.'; tar -cvf '.$file_tmp_backup.' . --exclude=.bak');

	//compress backup
	UI::out("Compressing backup\n");
	run('bzip2 '.$file_tmp_backup);

	//moving backup
	UI::out("Storing backup\n");
	@mkdir(dirname($file_backup),0755,true);
	run('/bin/cp -f --remove-destination '.$file_tmp_backup.'.bz2 '.$file_backup);

	//cleanup
	UI::out("Cleaning up\n");
	@unlink($file_db_backup);
	@unlink($file_tmp_backup.'.bz2');

	UI::out("Backup complete\n");
	return $file_backup;
}

function restore($name=null,$db_restore=null,$file=null){
	$tgtdef = TgtDef::_get();
	//deal with backup file
	if(is_null($name) && is_null($file)) throw new Exception('Backup name or file must be supplied to restore');
	if(!is_null($file)) $backup_file = $file;
	elseif(!is_null($name)) $backup_file = TARGET.'/.bak/'.$name.'.tar.bz2';
	if(!file_exists($backup_file)) throw new Exception('Backup file does not exist: '.$backup_file);

	UI::out("Starting to restore backup \"$backup_file\"\n");

	//restoring files
	UI::out("Restoring files\n");
	run('tar -xvjf '.$backup_file.' -C '.TARGET);

	//restore database
	$db_restore = !is_null($db_restore) ? $db_restore : $tgtdef->get('db-restore');
	if($db_restore){
		UI::out("Restoring database\n");
		run($db_restore.' < '.TARGET.'/.sql.bak');
	} else UI::out('WARNING: No db restore provided, database has NOT been restored');

	UI::out("Restoration complete\n");
	return $backup_file;
}

function migrate($dest,$db_dump=null){
	if(!$dest) throw new Exception('A migration destination must be provided');
	$tgtdef = TgtDef::_get();
	$time = time();
	UI::out("Starting backup\n");
	$backup_file = backup($time,$db_dump);
	$dest = $dest.'/.bak/'.basename($backup_file);

	//transport to destination
	UI::out("Transporting to destination: $dest\n");
	@mkdir(dirname($dest),0755,true);
	file_put_contents($dest,file_get_contents($backup_file));

	//tell the user how to restore the migrated install
	UI::out("Migration complete, please run:\n  lss -R=$time --db-restore=\"".$tgtdef->get('db-restore')."\" \non the destination\n");

	return $backup_file;
}

function clearCache(){
	run('rm -rf '.CACHE.'/* ');
	UI::out("Cache has been cleared, please update package DB\n");
}

function createPackage($fqn){
	if(is_null($fqn)) throw new Exception('Pacakge FQN must not be null');
	$def = new PkgDef($fqn,Def::READWRITE);
	unset($def);
	UI::out("Package has been created, please construct the def and then refactor\n");
}

function deletePackage($fqn){
	if(is_null($fqn)) throw new Exception('Pacakge FQN must not be null');
	unlink(PkgDef::getDefFile(mda_get($opts,'delete')));
	UI::out("Package Definition has been destroyed, the files must be removed manually");
}

function exportPackage($fqn,$mirror){
	if(is_null($fqn) || is_null($mirror)) throw new Exception('Package FQN and mirror location are required');
	$def = new PkgDef($fqn);
	$exp = new PkgExport($def);
	UI::out("Starting to compile package\n");
	UI::out($exp->compile());
	UI::out("Compressing package\n");
	UI::out($exp->compress());
	UI::out("Writing package\n");
	UI::out($exp->write(PkgExport::getDest($def,$mirror)));
	return true;
}

function refactorPackage($fqn,$dir){
	if(is_null($fqn)) throw new Exception('Pacakge FQN must not be null');
	if(is_null($dir) || !is_dir($dir)) throw new Exception('Must have a valid dir to read from');
	//read the def
	$def = new PkgDef($fqn);
	if(!isset($def->data['manifest']) || !count($def->data['manifest'])) throw new Exception('Package has no manifest cannot continue');

	//go through the manifest
	foreach($def->data['manifest'] as $file){
		UI::out("About to copy $file\n");
		$dest = ROOT.'/pkg/'.$fqn.'/'.$file;
		@mkdir(dirname($dest),0755,true);
		copy($dir.'/'.$file,$dest);
	}
}

//--------------------------
//Def Management Functions
//--------------------------
function setValue($def,$name=null,$value=null){
	if(is_null($name)) throw new Exception('Name of value to set must be present');
	$def->iostate = $def::READWRITE;
	$var = mda_get($def->data,$name);
	if(is_array($var)) throw new Exception('Cannot set the value of an array or the variable does not exist');
	mda_set($def->data,$value,$name);
	return true;
}

function getDef($arg=null){
	//if we are null return sys
	if(is_null($arg) || $arg === false || $arg == 'sys' || $arg == 'system') return LsDef::_get();
	else if($arg == 'usr' || $arg == 'user') return UsrDef::_get();
	else if($arg == 'tgt' || $arg == 'target') return TgtDef::_get();
	else {
		//try to see if we can get a package def
		$file = PkgDef::getDefFile($arg,true);
		if(!file_exists($file)) throw new Exception('Invalid def type provided, and no package by this name exists');
		return new PkgDef($arg);
	}
	return false;
}

function showDef($def){
	UI::out(print_r($def->data,true));
	return true;
}

function addValue($def,$name=null,$value=null){
	if(is_null($name)) throw new Exception('Name of value to add must be present');
	$def->iostate = $def::READWRITE;
	//prepare the array
	$var = mda_get($def->data,$name);
	if(!is_null($var) && !is_array($def->data[$name])) throw new Exception('Trying to add a value to a non array');
	mda_add($def->data,$value,$name);
	return true;
}

function delValue($def,$name=null,$value=null){
	if(is_null($name)) throw new Exception('Name of value to delete must be present');
	$def->iostate = $def::READWRITE;
	$var = mda_get($def->data,$name);
	if(is_null($var)) throw new Exception('Value array does not exist');
	//process
	if(is_null($value)) return mda_del($def->data,$name);
	else return mda_del_value($def->data,$value,$name);
}


function usage(){ 
	UI::out(<<<'HELP'
SYNOPSIS
    lss [OPTIONS] --update

    lss [OPTIONS] [--install --remove --purge] <packages>

    lss [OPTIONS] [--set --add --del] --name <name> --value <value>

    lss [OPTIONS] [UTILITY]

    lss [OPTIONS] [LOCAL DB]

lss [OPTIONS] [DEV]
OPTIONS
    --help     -h ..........   display help info
    --yes      -y ..........   answer yes to all user prompts
    --verbose  -v ..........   increase output
RUN-TIME SETTINGS
    --target   -t <tgt>.....   manually specify the target (this should be set into the respective def)
    --mirror   -m <mirror>..   specify external mirror (mainly for dev functions)
PACKAGE MANAGEMENT
    --update   -U ..........   sync the package database with the upstream
    --search   -s <keywords>   search the package database
    --upgrade  -u ..........   upgrade the current working tree
    --install  -i <pkgs> ...   install new packages to working tree
    --remove   -r <pkgs> ...   remove packages from working tree
    --purge    -p <pkgs> ...   remove packages and data the packages have left behind (includes database tables)
LOCAL DB
    --show-db  -S ..........   show the current package database
    --db-file     <file>....   show the local database using a specific database file
    --list     -l ..........   show locally installed packages for target
UTILITY
    --int-version <version>.   take full version and return integer value
    --clear-cache ..........   clear the local cache (must update db afterwards)
DEV
    --build-db -b ..........   build the database from def files (only works in a full source set)
    --export-db   ..........   export built database to mirror (requires --mirror)
SETTINGS
    --set         :<usr|tgt>   set a value for a def (user or target is optional, defaults to sys)
    --add         :<usr|tgt>   add a value to an array
    --del         :<usr|tgt>   remove a value from an array
    --name        <name>....   name of value to modify
    --value       <value....   value to set

HELP
);
}

