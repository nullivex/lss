#!/usr/bin/php
<?php

//figure out where we are
define('ROOT',dirname(dirname(__FILE__)));

//require sources
require_once('bin/lib/func.php');
require_once('bin/lib/phpkg.php');

//figure out our opts
$so = 'crv';
$lo = array('core','rebase','verbose','seeds:','roots:','leafs:','out:','tree:');
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
		$seed_path = '/seed/'.$seed.'/';
		//check if seed exists
		if(!is_dir(ROOT.$seed_path)){
			dolog('WARNING: seed '.$seed.' does not exist');
			continue;
		}
		//clone in seed contents
		dolog('install seed '.$seed);
		$manifest = readManifest(ROOT.$seed_path);
		foreach($manifest as $mani_item)
			run('cp -av '.ROOT.$seed_path.$mani_item.' '.$dest.$seed_path.$mani_item);
	}
	dolog('done installing seeds');
}

//copy the roots
if(gfa($opts,'roots')){
	dolog('starting to install roots');
	foreach(explode(',',gfa($opts,'roots')) as $root){
		$root_path = '/root/'.$root.'/';
		//check if seed exists
		if(!is_dir(ROOT.$root_path)){
			dolog('WARNING: root '.$root.' does not exist');
			continue;
		}
		//clone in root contents
		dolog('install root '.$root);
		$manifest = readManifest(ROOT.$root_path);
		foreach($manifest as $mani_item)
			run('cp -av '.ROOT.$root_path.$mani_item.' '.$dest.$root_path.$mani_item);
	}
	dolog('done installing roots');
}

//copy the leafs
if(gfa($opts,'leafs')){
	dolog('starting to install leafs');
	foreach(explode(',',gfa($opts,'leafs')) as $leaf){
		$leaf_path = '/leaf/'.$leaf.'/';
		//check if leaf exists
		if(!is_dir(ROOT.$leaf_path)){
			dolog('WARNING: leaf '.$leaf.' does not exist');
			continue;
		}
		//clone in leaf contents
		dolog('installing leaf '.$leaf);
		$manifest = readManifest(ROOT.$leaf_path);
		foreach($manifest as $mani_item)
			run('cp -av '.ROOT.$leaf_path.$mani_item.' '.$dest.$leaf_path.$mani_item);
	}
	dolog('done installing leafs');
}
