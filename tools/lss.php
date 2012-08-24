#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/func.php');
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgdb.php');
require_once(ROOT.'/tools/lib/usrdef.php');

//start the usrdef to handle default options
$usrdef = new UsrDef($_SERVER['USER']);

//set this to throw an error if there is no action
$noerror = false;

//figure our opts
$so = array(
	//command settings
	'h', //help
	'y', //yes
	'v', //verbose
	
	//package actions
	'u', //upgrade
	's:', //search
	'i:', //install
	'r:', //remove
	'p:', //purge
	
	//db actions
	'U', //update
	'b', //build-db
	'e', //export-db
	'S', //show-db
	
	//working dir
	't', //user working directory
	
	//mirrors
	'm:', //use mirror
);
$lo = array(
	//command settings
	'help',
	'yes',
	'verbose',
	
	//package actions
	'upgrade',
	'search:',
	'install:',
	'remove:',
	'purge:',
	
	//db actions
	'db-file:',
	'build-db',
	'export-db',
	'show-db',
	'update',
	
	//working dir
	'target:',
	
	//sources
	'mirror:',
	
	//defaults
	'default-mirror:',
	'default-target:',
	'default-cache:'
	
);
$opts = getopt(implode('',$so),$lo); unset($so,$lo);

//update defaults if needed
if(gfa($opts,'default-target')){
	$noerror = true;
	$usrdef->iostate = UsrDef::READWRITE;
	$usrdef->data['target'] = gfa($opts,'default-target');
}
if(gfa($opts,'default-mirror')){
	$noerror = true;
	$usrdef->iostate = UsrDef::READWRITE;
	$usrdef->data['mirror'] = gfa($opts,'default-mirror');
}
if(gfa($opts,'default-cache')){
	$noerror = true;
	$usrdef->iostate = UsrDef::READWRITE;
	$usrdef->data['cache'] = gfa($opts,'default-cache');
}

//figure out our target and mirror
target($opts,$usrdef); //sets the constant 'TARGET'
mirror($opts,$usrdef); //sets the constant 'MIRROR'
cache($usrdef); //sets the constant 'CACHE'

//figure out our answer status
if(!is_null(gfa($opts,'y')) || !is_null(gfa($opts,'yes'))) define('ANSWER_YES',true);

//figure out what we are going to do
foreach(array_keys($opts) as $act){
	switch($act){
		case 'help':
		case 'h':
			usage();
			exit;
			break;
		case 'upgrade':
		case 'u':
			upgrade(gfa($opts,'tree'));
			exit;
			break;
		case 'build-db':
		case 'b':
			$noerror=true;
			buildDb();
			break;
		case 'export-db':
		case 'e':
			$noerror=true;
			exportDb();
			break;
		case 'show-db':
		case 'S':
			$noerror=true;
			showDb($opts);
			break;
		case 'update':
		case 'U':
			$noerror=true;
			update();
			break;
		case 'search':
		case 's':
			search(gfa($opts,'search') ? gfa($opts,'search') : gfa($opts,'s'));
			exit;
			break;
		case 'install':
		case 'i':
			install(gfa($opts,'install') ? gfa($opts,'install') : gfa($opts,'i'));
			exit;
			break;
		case 'remove':
		case 'r':
			remove(gfa($opts,'tree'),(gfa($opts,'remove') ? gfa($opts,'remove') : gfa($opts,'r')));
			exit;
			break;
		case 'purge':
		case 'p':
			remove(gfa($opts,'tree'),(gfa($opts,'purge') ? gfa($opts,'purge') : gfa($opts,'p')),true);
			exit;
			break;
		default:
			continue;
			break;
	}
}

//no action was taken, error out
if($noerror === false) throw new Exception('No action supplied see --help for details');

//update our package db
function update(){
	PkgDb::update(MIRROR.'/pkg.db');
	echo "Package database updated.\n";
}

function buildDb(){
	PkgDb::_get(ROOT.'/pkg/pkg.db')->build();
	echo "Package database has been built.\n";
}

function exportDb(){
	PkgDb::export(ROOT.'/pkg/pkg.db',MIRROR.'/pkg.db');
	echo "Package database has been exported.\n";
}

function showDb($opts){
	if(gfa($opts,'db-file')) $dbfile = gfa($opts,'db-file');
	else $dbfile = MIRROR.'/pkg.db';
	echo PkgDb::_get($dbfile)->show();
	echo "Database dump complete.\n";
}

function upgrade($tree){echo "Upgrade\n";}

function search($keywords){
	echo "Searching for packages matching \"$keywords\"\n";
	$db = PkgDb::_get();
	echo $db->search($keywords);
}

function install($packages){
	$db = PkgDb::_get();
	//blow up packages and find them
	$pkgs = array();
	echo "Locating packages\n";
	foreach(explode(',',$packages) as $pkg_qn){
		//see if we can find the package
		try {
			$pkgs[] = $pkg = $db->find($pkg_qn);
		} catch(Exception $e){
			if($e->getCode() == 1 || $e->getCode() == 2){
				echo $e->getMessage()."\n";
				echo $db->search($pkg_qn);
				return false;
			} else throw $e;
		}
	}
	
	//now lets do some depsolving
	echo "Selecting dependencies if needed\n";
	foreach($pkgs as $pkg){
		foreach($db->getDeps($pkg['rowid']) as $dep){
			$pkgs[] = $db->getByFQN($dep['fqn']);
		}
	}
	
	//remove duplicates and print install list
	echo "Removing duplicates\n";
	$tmp_pkgs = array();
	foreach($pkgs as $key => $pkg) $tmp_pkgs[$key] = $pkg['fqn'];
	remove_dups($tmp_pkgs);
	foreach($pkgs as $key => $pkg){
		if(!in_array($key,array_keys($tmp_pkgs))) unset($pkgs[$key]);
	}
	echo "The following packages will be INSTALLED:\n";
	foreach($pkgs as $pkg) echo '  '.$pkg['fqn']."\n";
	
	if(!defined('ANSWER_YES')){
		if(!prompt_confirm("Are you sure you want to continue? (y/N):")) exit;
	}
	echo "Starting installation\n";
	
	//retrieve packages
	echo "Downloading packages from mirror\n";
	foreach($pkgs as $key => $pkg){
		echo '  Downloading '.$pkg['fqn'];
		$dest = CACHE.'/mirror/'.$pkg['fqn'].'.tar.bz2';
		$src = $pkgs[$key]['file'] = MIRROR.'/'.$pkg['fqn'].'.tar.bz2';
		@mkdir(dirname($dest),0755,true);
		$buff = @file_get_contents($src);
		if(!$buff) throw new Exception('Could not download package: '.$src);
		$rv = @file_put_contents($dest,$buff);
		if(!$rv) throw new Exception('Failed to save package: '.$dest);
		echo "... done\n";
	}
	
	//extract packages
	echo "Extracting packages to target\n";
	foreach($pkgs as $pkg){
		echo '  Extracting '.$pkg['fqn'];
		Pkg::extract($pkg['file'],TARGET);
		echo "... done\n";
	}
	
	//process hooks
	echo "Setting up new packages\n";
	foreach($pkgs as $pkg){
		echo '  Setting up '.$pkg['fqn'];
		$hook_file = '/tmp/xyzf'; //TODO: figure out where we are putting the hookfiles
		if(file_exists($hook_file)){
			exec_hook($hook_file,'install');
		}
		echo "... done\n";
	}
	
	//done
	echo "Install complete.\n";
	
}

function remove($tree,$packages,$purge=false){
	if($purge) echo "Purging $packages\n";
	else echo "Remove $packages\n";
}

function usage(){ 
echo <<<'HELP'
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

HELP;
}
