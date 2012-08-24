#!/usr/bin/php
<?php

require('boot.php');

//require sources
require_once(ROOT.'/tools/lib/func.php');
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgexport.php');
require_once(ROOT.'/tools/lib/usrdef.php');

//start the usrdef to handle default options
$usrdef = new UsrDef($_SERVER['USER']);

//figure out our opts
$so = 'm:';
$lo = array(
	'help',
	
	//package selction
	'pkg:',
	'repo:',
	
	//mirror
	'mirror:',
	
);
$opts = getopt($so,$lo); unset($so,$lo);

if(!is_null(gfa($opts,'help'))){
	displayHelp(); exit;
}

//validate package
$pkg = trim(gfa($opts,'pkg'));
if(!$pkg) throw new Exception('No package supplied: '.$pkg);
$repo = gfa($opts,'repo') ? gfa($opts,'repo') : REPO_MAIN;
if(!Pkg::exists($pkg,$repo)) throw new Exception('Package does not exist: '.$pkg);

//figure out mirror
mirror($opts,$usrdef);

if(gfa($opts,'help')){
	displayHelp();
	exit;
}

exportPkg($pkg,$repo);
exit;

//---------------------------------
//Control Functions
//---------------------------------

//create package
function exportPkg($pkg,$repo){
	$def = new PkgDef($pkg,$repo);
	$exp = new PkgExport($def);
	$exp->compile();
	$exp->compress();
	$exp->write(PkgExport::getDest($def));
	return true;
}

//help function
function displayHelp(){
echo <<<'HELP'
Example:
    bin/pkgexport --repo main --pkg util/func --mirror /usr/lss/mirror
Options:
 --help           ..........    display help info
 --pkg            ..........    package to use, SQN required
 --repo           ..........    repo to use, defaults to 'main'
 --mirror   -m    ..........    location of the mirror to export to (user default is used when omitted)

HELP;
}
