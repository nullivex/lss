#!/usr/bin/php
<?php

require('src/boot.php');

//require sources
require_once(ROOT.'/tools/lib/tgtdef.php');

//control funcs
require_once(ROOT.'/tools/src/func_lssadmin.php');

//set this to throw an error if there is no action
$noerror = false;

//figure our opts
$so = array(
	//command settings
	'h', //help
	'y', //yes
);
$lo = array(
	//command settings
	'help',
	'yes',

	//actions
	'build-deb',
	'build-core',
	'test-core',
	'install-man',
	'split-app:',
	
	//data
	'dir:',
	'mirror:',

);
$opts = getopt(implode('',$so),$lo); unset($so,$lo);

//figure out our answer status
if(!is_null(mda_get($opts,'y')) || !is_null(mda_get($opts,'yes'))) define('ANSWER_YES',true);

//figure out our target and mirror
target($opts); //sets the constant 'TARGET'
cache(); //sets the constant 'CACHE'

//init target def
TgtDef::init(TARGET);

//figure out what we are going to do
foreach(array_keys($opts) as $act){
	switch($act){
		case 'h':
		case 'help':
			usage();
			exit;
			break;
		case 'build-deb':
			buildDeb(mda_get($opts,'dir'));
			exit;
			break;
		case 'build-core':
			buildCore(mda_get($opts,'dir'),mda_get($opts,'mirror'));
			exit;
			break;
		case 'test-core':
			testCore(mda_get($opts,'dir'),mda_get($opts,'mirror'),TARGET);
			exit;
			break;
		case 'install-man':
			installMan(mda_get($opts,'dir'));
			exit;
			break;
		case 'split-app':
			splitApp(mda_get($opts,'split-app'));
			exit;
			break;
		default:
			continue;
			break;
	}
}

//no action was taken, error out
if($noerror === false) throw new Exception('No action supplied see --help for details',ERR_NO_ACTION);
