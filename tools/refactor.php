#!/usr/bin/php
<?php

require('src/boot.php');

//require sources
require_once(ROOT.'/tools/lib/pkg.php');
require_once(ROOT.'/tools/lib/pkgdef.php');

//figure out our opts
$so = '';
$lo = array(
	'help',
	'pkg:',
	'repo:',
	'tree:',
	'dir:'
);
$opts = getopt($so,$lo); unset($so,$lo);

//figure out working dir
if(gfa($opts,'dir')) $wdir = gfa($opts,'dir');
elseif(gfa($opts,'tree')) $wdir = ROOT.'/tree/'.gfa($opts,'tree');
else throw new Exception('No working directory provided, use --tree or --dir');

//validate package
$pkg = trim(gfa($opts,'pkg'));
if(!$pkg) throw new Exception('No package supplied: '.$pkg);
$repo = gfa($opts,'repo') ? gfa($opts,'repo') : REPO_MAIN;
if(!Pkg::exists($pkg,$repo)) throw new Exception('Package does not exist: '.$pkg);

//read the def
$def = new PkgDef($pkg,$repo,pkgDef::READONLY);
if(!isset($def->data['manifest'])) throw new Exception('Package has no manifest cannot continue');

//go through the manifest
foreach($def->data['manifest'] as $file){
	UI::out("About to copy $file\n");
	$dest = ROOT.'/pkg/'.$repo.'/'.$pkg.'/'.$file;
	@mkdir(dirname($dest),0755,true);
	copy($wdir.'/'.$file,$dest);
}

UI::out("Done\n");
