#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/func.php');
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');

//figure out our opts
$pkg = gfa($argv,1); unset($GLOBALS['argv'][1]);
$so = '';
$lo = array(
	'help',
	'repo:',
	'addfile:',
	'delfile:'
);
$opts = getopt($so,$lo); unset($so,$lo);

//validate package
if(!$pkg) throw new Exception('No package supplied: '.$pkg);
$repo = gfa($opts,'repo') ? gfa($opts,'repo') : REPO_MAIN;
if(!pkgExists($pkg,$repo)) throw new Exception('Package does not exist: '.$pkg);

foreach(array_keys($opts) as $act){
	switch($act){
		case 'addfile':
			addFile($pkg,$opts['addfile'],$repo);
			break;
		case 'delfile':
			delFile($pkg,$opts['delfile'],$repo);
			break;
		case 'help':
			displayHelp();
			break;
		default:
			//error out
			throw new Exception('No action supplied, see --help');
			break;
	}
}

//add a file
function addFile($pkg,$file,$repo){
	//grab the package def
	$def = readDef($pkg,$repo);
	//add to the manifest
	$def['manifest'][] = $file;
	$rv = defUpdate($pkg,$def,$repo);
	if(!$rv) throw new Exception('Failed to write new manifest file');
}

//remove file
function delFile($pkg,$file,$repo){
	$def = readManifest($mod_dir);
	foreach($def['manifest'] as &$file){
		if($file == gfa($opts,'remove')) unset($file);
	}
	$rv = defUpdate($pkg,$def,$repo);
	if(!$rv) throw new Exception('Failed to write new manifest file');
}

//help function
function displayHelp(){
	//TODO: write this function
}

