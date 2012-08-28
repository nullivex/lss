#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgdb.php');
require_once(ROOT.'/tools/lib/tgtdef.php');

//control funcs
require_once(ROOT.'/tools/lib/func_lss.php');

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
	'no-backup',
	
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
	
	//working dir
	'target:',
	
	//sources
	'mirror-add:',
	'mirror-del:',
	
	//misc/utility
	'int-version:',
	'clear-cache',
	
	//defaults
	'default-do-backup',
	'default-no-backup',
	'default-db-dump:',
	'default-db-restore:',
	'default-target:',
	'default-cache:',
	'default-ui:',
	
);
$opts = getopt(implode('',$so),$lo); unset($so,$lo);

//suppress system logging
if(is_null(gfa($opts,'v')) && is_null(gfa($opts,'verbose'))) define('OUT_QUIET',true);

//update defaults if needed
if(!is_null(gfa($opts,'default-target'))){
	$noerror = true;
	UsrDef::_get()->iostate = UsrDef::READWRITE;
	UsrDef::_get()->data['target'] = gfa($opts,'default-target');
}
if(!is_null(gfa($opts,'default-cache'))){
	$noerror = true;
	UsrDef::_get()->iostate = UsrDef::READWRITE;
	UsrDef::_get()->data['cache'] = gfa($opts,'default-cache');
}
if(!is_null(gfa($opts,'default-ui'))){
	$noerror = true;
	UsrDef::_get()->iostate = UsrDef::READWRITE;
	UsrDef::_get()->data['ui'] = gfa($opts,'default-ui');
}

//figure out our target and mirror
target($opts); //sets the constant 'TARGET'
mirror($opts); //sets the constant 'MIRROR'
cache(); //sets the constant 'CACHE'
$tgtdef = new TgtDef(TARGET);

//target defaults
if(gfa($opts,'default-db-dump')){
	$noerror = true;
	$tgtdef->iostate = $tgtdef::READWRITE;
	$tgtdef->data['db-dump'] = gfa($opts,'default-db-dump');
}
if(gfa($opts,'default-db-restore')){
	$noerror = true;
	$tgtdef->iostate = $tgtdef::READWRITE;
	$tgtdef->data['db-restore'] = gfa($opts,'default-db-restore');
}
if(!is_null(gfa($opts,'default-no-backup')) || !is_null(gfa($opts,'default-do-backup'))){
	$noerror = true;
	$tgtdef->iostate = $tgtdef::READWRITE;
	if(!is_null(gfa($opts,'default-no-backup'))) $tgtdef->data['no_backup'] = true;
	elseif(!is_null(gfa($opts,'default-do-backup'))) $tgtdef->data['no_backup'] = false;
	else unset($tgtdef->data['no_backup']);
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
			
		//mirror management
		case 'mirror-add':
			$noerror=true;
			mirrorAdd(gfa($opts,'mirror-add'));
			break;
		case 'mirror-del':
			$noerror=true;
			mirrorDel(gfa($opts,'mirror-del'));
			break;
			
		//package actions
		case 'upgrade':
		case 'u':
			upgrade($tgtdef,(!is_null(gfa($opts,'no-backup')) ? false : true));
			exit;
			break;
		case 'search':
		case 's':
			search(gfa($opts,'search') ? gfa($opts,'search') : gfa($opts,'s'));
			exit;
			break;
		case 'install':
		case 'i':
			install($tgtdef,gfa($opts,'install') ? gfa($opts,'install') : gfa($opts,'i'),false,(!is_null(gfa($opts,'no-backup')) ? false : true));
			exit;
			break;
		case 'remove':
		case 'r':
			remove($tgtdef,(gfa($opts,'remove') ? gfa($opts,'remove') : gfa($opts,'r')),false,(!is_null(gfa($opts,'no-backup')) ? false : true));
			exit;
			break;
		case 'purge':
		case 'p':
			remove($tgtdef,(gfa($opts,'purge') ? gfa($opts,'purge') : gfa($opts,'p')),true,(!is_null(gfa($opts,'no-backup')) ? false : true));
			exit;
			break;
			
		//backup/restore
		case 'backup':
		case 'B':
			backup($tgtdef,gfa($opts,'backup') ? gfa($opts,'backup') : gfa($opts,'B'),gfa($opts,'db-dump'));
			exit;
			break;
		case 'restore':
		case 'R':
			restore($tgtdef,gfa($opts,'restore') ? gfa($opts,'restore') : gfa($opts,'R'),gfa($opts,'db-restore'),gfa($opts,'restore-file'));
			exit;
			break;
			
		//migrate
		case 'migrate':
			migrate($tgtdef,gfa($opts,'migrate'));
			exit;
			break;
		
		//local package actions
		case 'list':
		case 'l':
			listInstalled($tgtdef);
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
