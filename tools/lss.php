#!/usr/bin/php
<?php

require('src/boot.php');

//require sources
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgdb.php');
require_once(ROOT.'/tools/lib/tgtdef.php');
require_once(ROOT.'/tools/lib/db.php');

//control funcs
require_once(ROOT.'/tools/src/func_lss.php');

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
	'l', //list installed

	//backup/restore
	'B::', //backup
	'R::', //restore

	//db actions
	'U', //update
	'b', //build-db
	'e', //export-db
	'S', //show-db

	//working dir
	't:', //user working directory

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
	'list',

	//backup/restore
	'backup::',
	'restore::',
	'restore-file:',
	'migrate:',
	'backup-list',
	'db-dump:',
	'db-restore:',

	//db actions
	'db-file:',
	'build-db',
	'export-db',
	'show-db',
	'update',

	//mirror
	'mirror:',

	//working dir
	'target:',

	//misc/utility
	'int-version:',
	'clear-cache',

	//set options
	'set::',
	'add::',
	'del::',
	'name:',
	'value:',

);
$opts = getopt(implode('',$so),$lo); unset($so,$lo);

//suppress system logging
if(is_null(gfa($opts,'v')) && is_null(gfa($opts,'verbose'))) define('OUT_QUIET',true);

//figure out our target and mirror
target($opts); //sets the constant 'TARGET'
cache(); //sets the constant 'CACHE'

//init target def
TgtDef::init(TARGET);

//set options
if( !is_null(gfa($opts,'set')) || !is_null(gfa($opts,'add')) || !is_null(gfa($opts,'del')) ){
	//figure out def type
	if(!is_null(gfa($opts,'set'))) $type = gfa($opts,'set');
	else if(!is_null(gfa($opts,'add'))) $type = gfa($opts,'add');
	else if(!is_null(gfa($opts,'del'))) $type = gfa($opts,'del');
	else $type = 'sys';
	switch($type){
		case 'target':
			$def = TgtDef::_get();
			break;
		case 'user':
			$def = UsrDef::_get();
			break;
		case 'sys':
			$def = LsDef::_get();
			break;
		default:
			throw new Exception('Invalid type "'.$type.'" (expecting [target|user|sys])');
			break;
	}
	//figure out value action
	try {
		if(!is_null(gfa($opts,'set'))) setValue($def,gfa($opts,'name'),gfa($opts,'value'));
		else if(!is_null(gfa($opts,'add'))){
			if(is_null(gfa($opts,'value'))) throw new Exception('Adding requires --value');
			if(gfa($opts,'name') == 'mirrorauth'){
				if(is_null(gfa($opts,'mirror'))) throw new Exception('Adding mirrorauth requires --mirror');
				addValue($def,gfa($opts,'name'),gfa($opts,'value'),gfa($opts,'mirror'));
			} else
				addValue($def,gfa($opts,'name'),gfa($opts,'value'));
		} else if(!is_null(gfa($opts,'del'))){
			if(gfa($opts,'name') == 'mirrorauth'){
				if(is_null(gfa($opts,'mirror'))) throw new Exception('Deleting mirrorauth requires --mirror');
				delValue($def,gfa($opts,'name'),null,gfa($opts,'mirror'));
			} else {
				if(is_null(gfa($opts,'value'))) throw new Exception('Deleting requires --value');
				delValue($def,gfa($opts,'name'),gfa($opts,'value'));
			}
		} else throw new Exception('No proper action submitted for value modification');
	} catch(Exception $e){
		//we dont want to write on error
		$def->iostate = $def::READONLY;
		//throw the original exception so the upstream can handle it
		throw $e;
	}
	exit;
}

//figure out our answer status
if(!is_null(gfa($opts,'y')) || !is_null(gfa($opts,'yes'))) define('ANSWER_YES',true);

//figure out what we are going to do
foreach(array_keys($opts) as $act){
	switch($act){
		//help
		case 'help':
		case 'h':
			usage();
			exit;
			break;

		//db actions
		case 'build-db':
		case 'b':
			$noerror=true;
			buildDb();
			break;
		case 'export-db':
		case 'e':
			$noerror=true;
			exportDb(gfa($opts,'mirror'));
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

		//package actions
		case 'upgrade':
		case 'u':
			upgrade();
			exit;
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
			remove((gfa($opts,'remove') ? gfa($opts,'remove') : gfa($opts,'r')));
			exit;
			break;
		case 'purge':
		case 'p':
			remove((gfa($opts,'purge') ? gfa($opts,'purge') : gfa($opts,'p')),true);
			exit;
			break;

		//backup/restore
		case 'backup':
		case 'B':
			backup(gfa($opts,'backup') ? gfa($opts,'backup') : gfa($opts,'B'),gfa($opts,'db-dump'));
			exit;
			break;
		case 'restore':
		case 'R':
			restore(gfa($opts,'restore') ? gfa($opts,'restore') : gfa($opts,'R'),gfa($opts,'db-restore'),gfa($opts,'restore-file'));
			exit;
			break;

		//migrate
		case 'migrate':
			migrate(gfa($opts,'migrate'));
			exit;
			break;

		//local package actions
		case 'list':
		case 'l':
			listInstalled();
			exit;
			break;

		//misc/utility
		case 'int-version':
			intVersion(gfa($opts,'int-version'));
			exit;
			break;

		//default
		default:
			continue;
			break;
	}
}

//no action was taken, error out
if($noerror === false) throw new Exception('No action supplied see --help for details');
