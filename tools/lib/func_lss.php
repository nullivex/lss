<?php

//update our package db
function update(){
	PkgDb::update(MIRROR.'/pkg.db');
	UI::out("Package database updated.\n");
}

function buildDb(){
	PkgDb::_get(ROOT.'/pkg/pkg.db')->build();
	UI::out("Package database has been built.\n");
}

function exportDb(){
	PkgDb::export(ROOT.'/pkg/pkg.db',MIRROR.'/pkg.db');
	UI::out("Package database has been exported.\n");
}

function showDb($opts){
	if(gfa($opts,'db-file')) $dbfile = gfa($opts,'db-file');
	else $dbfile = MIRROR.'/pkg.db';
	UI::out(PkgDb::_get($dbfile)->show());
	UI::out("Database dump complete.\n");
}

function upgrade($tgtdef,$backup=true){
	UI::out("Upgrading ".UsrDef::_get()->get('target')."\n");
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
	install($tgtdef,trim($pkg_str,','),true,$backup);
	
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

function search($keywords){
	UI::out("Searching for packages matching \"$keywords\"\n");
	UI::out(PkgDb::_get()->search($keywords));
}

function install($tgtdef,$packages,$upgrade=false,$backup=true){
	//create a backup
	if($backup === true && $tgtdef->get('no_backup') != true) backup($tgtdef);
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
			$pkgs[] = $db->getByFQN($dep['fqn']);
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
		$ui->out("No packages to be removed.\n",true);
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
		$dest = CACHE.'/mirror/'.$pkg['fqn'].'.tar.bz2';
		$src = $pkgs[$key]['file'] = MIRROR.'/'.$pkg['fqn'].'.tar.bz2';
		@mkdir(dirname($dest),0755,true);
		$buff = @file_get_contents($src);
		if(!$buff) throw new Exception('Could not download package: '.$src);
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
		foreach($pkgs as $pkg){
			$ui->out('  Setting up '.$pkg['fqn']);
			$hook_file = Pkg::hookFile($pkg['fqn']);
			if(file_exists($hook_file)){
				exec_hook($hook_file,'install');
			}
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

function remove($tgtdef,$packages,$purge=null,$backup=true){
	//create a backup
	if($backup === true && $tgtdef->get('no_backup') != true) backup($tgtdef);
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

function listInstalled($tgtdef){
	UI::out('Currently installed packages at '.UsrDef::_get()->get('target')."\n\n");
	UI::out('  PACKAGE'.str_repeat(' ',65).'VERSION'."\n");
	foreach($tgtdef->get('installed') as $pkg => $version)
		UI::out("  $pkg".str_repeat(' ',72 - strlen($pkg))."$version\n");
	UI::out("\n");
	return;
}

function backup($tgtdef,$name=null,$db_dump=null){
	//deal with name
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

function restore($tgtdef,$name=null,$db_restore=null,$file=null){
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

function migrate($tgtdef,$dest,$db_dump=null){
	if(!$dest) throw new Exception('A migration destination must be provided');
	$time = time();
	UI::out("Starting backup\n");
	$backup_file = backup($tgtdef,$time,$db_dump);
	$dest = $dest.'/.bak/'.basename($backup_file);
	
	//transport to destination
	UI::out("Transporting to destination: $dest\n");
	@mkdir(dirname($dest),0755,true);
	file_put_contents($dest,file_get_contents($backup_file));
	
	//tell the user how to restore the migrated install
	UI::out("Migration complete, please run:\n  lss -R=$time --db-restore=\"".$tgtdef->get('db-restore')."\" \non the destination\n");
	
	return $backup_file;
}

function intVersion($version){
	Ui::out(Pkg::v2i($version)."\n");
	return true;
}
	
function usage(){ 
	UI::out(<<<'HELP'
Options:
 --help    	-h ..........	display help info
 --yes     	-y ..........	answer yes to all user prompts
 --verbose 	-v ..........	increase output
 --rebase  	-R ..........	rebase the tree to build to (dangerous)
 --search  	-s <keywords>	search the package database
 --update  	-U ..........	sync the package database with the upstream
 --upgrade 	-u ..........	upgrade the current working tree
 --install 	-i <pkgs> ...	install new packages to working tree
 --remove  	-r <pkgs> ...	remove packages from working tree
 --purge   	-p <pkgs> ...	remove packages and data the packages have left behind (includes database tables)
 --build-db	-b ..........	build the database from def files (only works in a full source set)
 --show-db 	-S ..........	show the current package database
 --local   	-l ..........	source the packages from a local source set

HELP
);
}