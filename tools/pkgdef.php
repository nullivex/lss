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
	
	//package selction
	'pkg:',
	'repo:',
	
	//create
	'create',
	
	//update args
	'update',
	'pkg-version:',
	
	//delete
	'delete',
	
	//dep args
	'adddep',
	'deldep',
	'dep-pkg:',
	'dep-version:',
	
	//manifest args
	'addfile:',
	'delfile:'
);
$opts = getopt($so,$lo); unset($so,$lo);

//validate package
$pkg = gfa($opts,'pkg');
if(!$pkg) throw new Exception('No package supplied: '.$pkg);
$repo = gfa($opts,'repo') ? gfa($opts,'repo') : REPO_MAIN;
if(!Pkg::exists($pkg,$repo) && is_null(gfa($opts,'create'))) throw new Exception('Package does not exist: '.$pkg);

//route the call
foreach(array_keys($opts) as $act){
	switch($act){
		case 'create':
			createDef($repo,$pkg);
			exit;
		case 'update':
			updateDef(
				$repo,
				$pkg,
				gfa($opts,'pkg-version')
			);
			exit;
		case 'delete':
			deleteDef($repo,$pkg);
			exit;
		case 'adddep':
			addDep($repo,$pkg,gfa($opts,'dep-pkg'),gfa($opts,'dep-version'));
			exit;
		case 'deldep':
			delDep($repo,$pkg,gfa($opts,'dep-pkg'));
			exit;
		case 'addfile':
			addFile($repo,$pkg,$opts['addfile']);
			exit;
		case 'delfile':
			delFile($repo,$pkg,$opts['delfile']);
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

//create package
function createDef($repo,$pkg){
	//create the def
	PkgDef::createPkg($repo,$pkg);
	//create the pkg root
	Pkg::create($pkg,$repo);
	return true;
}

//update package info
function updateDef($repo,$pkg_sqn,$version=null){
	$def = new PkgDef($pkg_sqn,$repo,PkgDef::READWRITE);
	if(!is_null($version)) $def->data['info']['version'] = $version;
	return true;
}

function deleteDef($repo,$pkg){
	PkgDef::deletePkg($repo,$pkg);
	echo 'The package folder must be manually removed: '.Pkg::pkgPath($pkg,$repo)."\n";
	echo "Please rebuild the package DB.\n";
	return;
}

//add dep
function addDep($repo,$pkg,$dep_pkg,$dep_versions){
	$def = new PkgDef($pkg,$repo,PkgDef::READWRITE);
	$def->data['dep'][$dep_pkg] = array('versions'=>explode(',',$dep_versions));
	return true;
}

//del dep
function delDep($repo,$pkg,$dep_pkg){
	$def = new PkgDef($pkg,$repo,PkgDef::READWRITE);
	foreach($def->data['dep'] as $key => $dep){
		if($key == $dep_pkg){
			unset($def->data['dep'][$key]);
			break;
		}
	}
	return true;
}

//add a file
function addFile($repo,$pkg,$file){
	//grab the package def
	$def = new PkgDef($pkg,$repo,PkgDef::READWRITE);
	//add to the manifest
	$def->data['manifest'][] = $file;
	return true;
}

//remove file
function delFile($repo,$pkg,$file){
	$def = new PkgDef($pkg,$repo,PkgDef::READWRITE);
	foreach($def->data['manifest'] as $key => $f){
		if($f == $file){
			unset($def->data['manifest'][$key]);
			break;
		}
	}
	return true;
}

//help function
function displayHelp(){
	//TODO: write this function
}
