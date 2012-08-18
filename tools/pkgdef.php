#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/func.php');
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');

//figure out our opts
$so = '';
$lo = array(
	'help',
	'pkg:',
	'repo:',
	'addfile:',
	'delfile:'
);
$opts = getopt($so,$lo); unset($so,$lo);

//validate package
$pkg = gfa($opts,'pkg');
if(!$pkg) throw new Exception('No package supplied: '.$pkg);
$repo = gfa($opts,'repo') ? gfa($opts,'repo') : REPO_MAIN;
if(!pkgExists($pkg,$repo)) throw new Exception('Package does not exist: '.$pkg);

//route the call
foreach(array_keys($opts) as $act){
	switch($act){
		case 'addfile':
			addFile($pkg,$opts['addfile'],$repo);
			exit;
		case 'delfile':
			delFile($pkg,$opts['delfile'],$repo);
			exit;
		case 'help':
			displayHelp();
			exit;
		default:
			continue;
			break;
	}
}

//error out if we get here
throw new Exception('No action supplied, see --help');

//---------------------------------
//Control Functions
//---------------------------------

//add a file
function addFile($pkg,$file,$repo){
	//grab the package def
	$def = readDef($repo.'/'.$pkg);
	//add to the manifest
	if(!is_array($def['manifest'])) $def['manifest'] = array();
	$def['manifest'][] = $file;
	$rv = defUpdate($pkg,$def,$repo);
	if(!$rv) throw new Exception('Failed to update package definition');
}

//remove file
function delFile($pkg,$file,$repo){
	$def = readManifest($mod_dir);
	foreach($def['manifest'] as &$file){
		if($file == gfa($opts,'remove')) unset($file);
	}
	$rv = defUpdate($pkg,$def,$repo);
	if(!$rv) throw new Exception('Failed to update package definition');
}

//help function
function displayHelp(){
	//TODO: write this function
}

