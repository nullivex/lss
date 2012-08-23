#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/func.php');
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgdb.php');
require_once(ROOT.'/tools/lib/usrdef.php');

var_dump($GLOBALS); exit;

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
	'build-db',
	'show-db',
	'update',
	
	//working dir
	'target:',
	
	//sources
	'mirror:',
	
	//defaults
	'default-mirror:',
	'default-target:'
);
$opts = getopt(implode('',$so),$lo); unset($so,$lo);

//update defaults if needed
if(gfa($opts,'default-target')) defaultTarget(gfa($opts,'default-target'));
if(gfa($opts,'default-mirror')) defaultMirror(gfa($opts,'default-mirror'));

//figure out our target and mirror
target(); //sets the constant 'TARGET'
mirror(); //sets the constant 'MIRROR'

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
			buildDb();
			exit;
			break;
		case 'show-db':
		case 's':
			showDb();
			exit;
			break;
		case 'update':
		case 'U':
			update();
			exit;
			break;
		case 'search':
		case 'S':
			search(gfa($opts,'search') ? gfa($opts,'search') : gfa($opts,'s'));
			exit;
			break;
		case 'install':
		case 'i':
			install(gfa($opts,'tree'),(gfa($opts,'install') ? gfa($opts,'install') : gfa($opts,'i')));
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
throw new Exception('No action supplied see --help for details');

//update our package db
function update(){
	PkgDb::_get()->update();
	echo "Package database updated.\n";
}

function buildDb(){
	PkgDb::_get()->build();
	echo "Package database has been built.\n";
}

function showDb(){
	echo PkgDb::_get()->show();
	echo "Database dump complete.\n";
}

function upgrade($tree){echo "Upgrade\n";}
function search($keywords){echo "Search\n";}
function install($tree,$packages){echo "Install $packages\n";}
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

//utility
function mirror($opts,$usrdef){
	if(gfa($opts,'mirror')) $mirror = gfa($opts,'mirror');
	elseif(gfa($opts,'m')) $mirror = gfa($opts,'m');
	elseif(gfa($usrdef,'mirror')) $mirror = gfa($usrdef,'mirror');
	else $mirror = DEFAULT_MIRROR;
	define('MIRROR',$mirror);
}

function target($opts,$usrdef){
	if(gfa($opts,'target')) $target = gfa($opts,'target');
	elseif(gfa($opts,'t')) $target = gfa($opts,'t');
	elseif(gfa($usrdef,'target')) $target = gfa($usrdef,'target');
	else $target = DEFAULT_TARGET;
	define('TARGET',$target);
}