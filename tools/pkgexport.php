#!/usr/bin/php
<?php

require('src/boot.php');

//require sources
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');
require_once(ROOT.'/tools/lib/pkgexport.php');

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

if(is_null(gfa($opts,'mirror'))) throw new Exception('Mirror must be set to export');

if(gfa($opts,'help')){
	displayHelp();
	exit;
}

exportPkg($pkg,$repo,gfa($opts,'mirror'));
exit;

//---------------------------------
//Control Functions
//---------------------------------

//create package
function exportPkg($pkg,$repo,$mirror){
	$def = new PkgDef($pkg,$repo);
	$exp = new PkgExport($def);
	UI::out("Starting to compile package\n");
	UI::out($exp->compile());
	UI::out("Compressing package\n");
	UI::out($exp->compress());
	UI::out("Writing package\n");
	UI::out($exp->write(PkgExport::getDest($def,$mirror)));
	return true;
}

//help function
function displayHelp(){
	UI::out(<<<'HELP'
Example:
    bin/pkgexport --repo main --pkg util/func --mirror /usr/lss/mirror
Options:
 --help           ..........    display help info
 --pkg            ..........    package to use, SQN required
 --repo           ..........    repo to use, defaults to 'main'
 --mirror   -m    ..........    location of the mirror to export to (user default is used when omitted)

HELP
);
}
