#!/usr/bin/php
<?php

require('src/boot.php');

//require sources
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgdb.php');
require_once(ROOT.'/tools/lib/pkgexport.php');
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

	//target
	'target:',
	
	//working dir
	'dir:',

	//misc/utility
	'int-version:',
	'clear-cache',

	//def management options
	'set::',
	'add::',
	'del::',
	'show::',
	'name:',
	'value:',
	'key:',
	
	//pkg specific
	'create:',
	'delete:',
	'export:',
	'refactor:',

);
$opts = getopt(implode('',$so),$lo); unset($so,$lo);

//suppress system logging
if(is_null(mda_get($opts,'v')) && is_null(mda_get($opts,'verbose'))) define('OUT_QUIET',true);

//figure out our target and mirror
target($opts); //sets the constant 'TARGET'
cache(); //sets the constant 'CACHE'

//init target def
TgtDef::init(TARGET);

//figure out our answer status
if(!is_null(mda_get($opts,'y')) || !is_null(mda_get($opts,'yes'))) define('ANSWER_YES',true);

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
			exportDb(mda_get($opts,'mirror'));
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
			search(mda_get($opts,'search') ? mda_get($opts,'search') : mda_get($opts,'s'));
			exit;
			break;
		case 'install':
		case 'i':
			install(mda_get($opts,'install') ? mda_get($opts,'install') : mda_get($opts,'i'));
			exit;
			break;
		case 'remove':
		case 'r':
			remove((mda_get($opts,'remove') ? mda_get($opts,'remove') : mda_get($opts,'r')));
			exit;
			break;
		case 'purge':
		case 'p':
			remove((mda_get($opts,'purge') ? mda_get($opts,'purge') : mda_get($opts,'p')),true);
			exit;
			break;

		//backup/restore
		case 'backup':
		case 'B':
			backup(mda_get($opts,'backup') ? mda_get($opts,'backup') : mda_get($opts,'B'),mda_get($opts,'db-dump'));
			exit;
			break;
		case 'restore':
		case 'R':
			restore(mda_get($opts,'restore') ? mda_get($opts,'restore') : mda_get($opts,'R'),mda_get($opts,'db-restore'),mda_get($opts,'restore-file'));
			exit;
			break;

		//migrate
		case 'migrate':
			migrate(mda_get($opts,'migrate'));
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
			intVersion(mda_get($opts,'int-version'));
			exit;
			break;
		case 'clear-cache':
			clearCache();
			exit;
			break;
			
		//pkg management
		case 'create':
			createPackage(mda_get($opts,'create'));
			exit;
			break;
			
		case 'delete':
			deletePackage(mda_get($opts,'delete'));
			exit;
			break;
			
		case 'export':
			exportPackage(mda_get($opts,'export'),mda_get($opts,'mirror'));
			exit;
			break;
			
		case 'refactor':
			refactorPackage(mda_get($opts,'refactor'),mda_get($opts,'dir'));
			exit;
			break;
			
		//def management
		case 'show':
			$def = getDef(mda_get($opts,'show'));
			showDef($def);
			exit;
			break;
		case 'set':
			$def = getDef(mda_get($opts,'set'));
			setValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
			exit;
			break;
		case 'add':
			$def = getDef(mda_get($opts,'add'));
			addValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
			exit;
			break;
		case 'del':
			$def = getDef(mda_get($opts,'del'));
			delValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
			exit;
			break;
			/*
			//set options
			if( !is_null(mda_get($opts,'set')) || !is_null(mda_get($opts,'add')) || !is_null(mda_get($opts,'del')) || !is_null(mda_get($opts,'show')) ){
				//figure out def type
				if(!is_null(mda_get($opts,'set'))) $type = mda_get($opts,'set');
				else if(!is_null(mda_get($opts,'add'))) $type = mda_get($opts,'add');
				else if(!is_null(mda_get($opts,'del'))) $type = mda_get($opts,'del');
				else if(!is_null(mda_get($opts,'pkg'))) $type = 'pkg';
				else $type = 'sys';
				switch($type){
					case 'target':
					case 'tgt':
						$def = TgtDef::_get();
						break;
					case 'user':
					case 'usr':
						$def = UsrDef::_get();
						break;
					case 'sys':
						$def = LsDef::_get();
						break;
					case 'pkg':
						$def = new PkgDef(mda_get($opts,'pkg'));
						break;
					default:
						throw new Exception('Invalid type "'.$type.'" (expecting [target|user|sys])');
						break;
				}
				//figure out value action
				try {
					if(!is_null(mda_get($opts,'set'))) setValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
					else if(!is_null(mda_get($opts,'show'))) showDef($def);
					else if(!is_null(mda_get($opts,'add'))){
						if(is_null(mda_get($opts,'value'))) throw new Exception('Adding requires --value');
						if(mda_get($opts,'name') == 'mirrorauth'){
							if(is_null(mda_get($opts,'mirror'))) throw new Exception('Adding mirrorauth requires --mirror');
							addValue($def,mda_get($opts,'name'),mda_get($opts,'value'),mda_get($opts,'mirror'));
						} else if(!is_null(mda_get($opts,'dep'))){
							addValue($def,mda_get($opts,'name'),mda_get($opts,'value'),mda_get($opts,'dep'));
						} else
							addValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
					} else if(!is_null(mda_get($opts,'del'))){
						if(mda_get($opts,'name') == 'mirrorauth'){
							if(is_null(mda_get($opts,'mirror'))) throw new Exception('Deleting mirrorauth requires --mirror');
							delValue($def,mda_get($opts,'name'),null,mda_get($opts,'mirror'));
						} else if(!is_null(mda_get($opts,'dep'))){
							addValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
						} else {
							if(is_null(mda_get($opts,'value'))) throw new Exception('Deleting requires --value');
							delValue($def,mda_get($opts,'name'),mda_get($opts,'value'));
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
*/
			break;

		//default
		default:
			continue;
			break;
	}
}

//no action was taken, error out
if($noerror === false) throw new Exception('No action supplied see --help for details');
