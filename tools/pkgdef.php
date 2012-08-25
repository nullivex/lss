#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');

//load the User Interface
require_once(ROOT.'/tools/lib/ui.php');
$ui = UI::_get(DEFAULT_UI);

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
	'pkg-description:',
	
	//show def
	'show',
	
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

if(!is_null(gfa($opts,'help'))){
	displayHelp();
	exit;
}

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
				gfa($opts,'pkg-version'),
				gfa($opts,'pkg-description')
			);
			exit;
		case 'show':
			showDef($repo,$pkg);
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
function updateDef($repo,$pkg_sqn,$version=null,$description=null){
	$def = new PkgDef($pkg_sqn,$repo,PkgDef::READWRITE);
	if(!is_null($version)) $def->data['info']['version'] = $version;
	if(!is_null($description)) $def->data['info']['description'] = $description;
	return true;
}

//show the def
function showDef($repo,$pkg){
	$def = new PkgDef($pkg,$repo);
	UI::out(print_r($def->data,true));
	return true;
}

function deleteDef($repo,$pkg){
	PkgDef::deletePkg($repo,$pkg);
	UI::out('The package folder must be manually removed: '.Pkg::pkgPath($pkg,$repo)."\n");
	UI::out("Please rebuild the package DB.\n");
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
	UI::out(<<<'HELP'
Example:
    bin/pkgdef --repo main --pkg util/func --update --pkg-version=0.0.1 --pkg-description="Global functions"
Options:
 --help           ..........    display help info
 --pkg            ..........    package to use, SQN required
 --repo           ..........    repo to use, defaults to 'main'
 --create         ..........    create def file and package root
 --delete         ..........    delete def file (package root must be removed manually)
 --update         ..........    update package information
   --pkg-version  ..........    set the package version
   --pkg-description .......    set the package description
 --addfile        ..........    add a file to the package manifest
 --delfile        ..........    remove a file from the package manifest
 --adddep         ..........    add a dependency to the package
   --dep-pkg      ..........    FQN of the dep to be added
   --dep-version  ..........    CSV of compatible versions
 --deldep         ..........    remove a dep from a package
   --dep-pkg      ..........    FQN of dep to be removed

HELP
);
}
