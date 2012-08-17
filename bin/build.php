#!/usr/bin/php
<?php

//figure out where we are
define('ROOT',dirname(dirname(__FILE__)));

//require sources
require_once('bin/lib/func.php');
require_once('bin/lib/phpkg.php');

//figure out our opts
$so = 'crv';
$lo = array('core','rebase','verbose','seeds:','leafs:','out:','tree:');
$opts = getopt($so,$lo); unset($so,$lo);

//check if we are supposed to be verbose or even quiet
if(gfa($opts,'v') || gfa($opts,'verbose')) define('OUT_VERBOSE',true);

//figure out a destination
if(!gfa($opts,'out') && !gfa($opts,'tree')) throw new Exception('No build destination');
$dest = gfa($opts,'out') ? gfa($opts,'out') : ROOT . '/tree/' . gfa($opts,'tree');

//should we rebase?
if(!is_null(gfa($opts,'rebase')) || !is_null(gfa($opts,'r'))){
	dolog('rebasing the destination: '.$dest);
	run('rm -rv '.$dest);
}

//make sure the dest exists
dolog('creating our destination if it doesnt exist: '.$dest);
run('mkdir -pv '.$dest);

//copy the core
if(!is_null(gfa($opts,'core')) || !is_null(gfa($opts,'c'))){
	dolog('copying the core');
	run('cp -av '.ROOT.'/core/* '.$dest);
}

//copy the seeds
if(gfa($opts,'seeds')){
	dolog('starting to install seeds');
	foreach(explode(',',gfa($opts,'seeds')) as $seed){
		$seed_path = ROOT.'/seed/'.$seed.'/';
		//check if seed exists
		if(!is_dir($seed_path)){
			dolog('WARNING: seed '.$seed.' does not exist');
			continue;
		}
		//clone in seed contents
		dolog('install seed '.$seed);
		$manifest = readManifest($seed_path);
		foreach($manifest as $mani_item)
			run('cp -av '.$seed_path.$mani_item.' '.$dest);
	}
	dolog('done installing seeds');
}

//copy the leafs
if(gfa($opts,'leafs')){
	dolog('starting to install leafs');
	foreach(explode(',',gfa($opts,'leafs')) as $leaf){
		$leaf_path = ROOT.'/leaf/'.$leaf.'/';
		//check if leaf exists
		if(!is_dir($leaf_path)){
			dolog('WARNING: leaf '.$leaf.' does not exist');
			continue;
		}
		//clone in leaf contents
		dolog('installing leaf '.$leaf);
		$manifest = readManifest($leaf_path);
		foreach($manifest as $mani_item)
			run('cp -av '.$leaf_path.$mani_item.' '.$dest);
	}
	dolog('done installing leafs');
}
