<?php

function gfa($data,$args=null){
	if(!is_array($args)){
		$args = func_get_args();
		array_shift($args);
	}
	$var = ''; foreach($args as $arg) $var .= '[\''.$arg.'\']';
	eval('$val = isset($data'.$var.') ? $data'.$var.' : null;');
	return $val;
}

function dfa(&$data){
	$args = func_get_args();
	array_shift($args);
	$var = ''; foreach($args as $arg) $var .= '[\''.$arg.'\']';
	eval('@unset($data'.$var.');');
	return true;
}

function run($cmd,&$return=null){
	$output = '';
	$cmd = '/bin/bash -c "'.addslashes($cmd).'"';
	exec($cmd,$output,$return);
	$output = implode("\n",$output);
	dolog($cmd.': '.$output);
	return $output;
}

function dolog($msg,$output=true){
	$msg = date('m/d/y g:i:s').' -- '.$msg;
	if($output && !defined('OUT_QUIET')) UI::out($msg."\n");
	$handle = fopen('/var/log/openlss','a');
	fwrite($handle,$msg."\n");
	fclose($handle);
}

function file_array($path,$exclude='',$recursive=false){
	$path = rtrim($path,'/') . '/';
	if(!is_dir($path)) return array(rtrim($path,'/'));
	$folder_handle = opendir($path);
	$exclude_array = array_merge(array('.','..'),explode('|',$exclude));
	$result = array();
	while(false !== ($filename = readdir($folder_handle))){
		if(!in_array($filename,$exclude_array)){
			$subpath = $path . $filename . '/';
			if(is_dir($subpath)){
				if($recursive) $result = array_merge($result,file_array($subpath,$exclude,true));
			} else {
				$result[] = $path.$filename;
			}
		}
	}
	sort($result);
	return $result;
}

function mirror($opts){
	if(gfa($opts,'mirror')) $mirror = gfa($opts,'mirror');
	elseif(gfa($opts,'m')) $mirror = gfa($opts,'m');
	elseif(UsrDef::_get()->get('mirror')) $mirror = UsrDef::_get()->get('mirror');
	else $mirror = DEFAULT_MIRROR;
	define('MIRROR',$mirror);
}

function target($opts){
	if(gfa($opts,'target')) $target = gfa($opts,'target');
	elseif(gfa($opts,'t')) $target = gfa($opts,'t');
	elseif(UsrDef::_get()->get('target')) $target = UsrDef::_get()->get('target');
	else $target = DEFAULT_TARGET;
	define('TARGET',$target);
}

function cache(){
	if(UsrDef::_get()->get('cache')) $cache = UsrDef::_get()->get('cache');
	else $cache = DEFAULT_CACHE;
	define('CACHE',$cache);
}

function remove_dups(&$arr){
	//deal with uniques in the manifest
	$tmp = array_unique($arr,SORT_STRING);
	sort($tmp,SORT_STRING);
	$arr = array_merge($tmp);
	unset($tmp);
}

function exec_hook($hook_file,$act){
	include($hook_file);
	if(isset($$act) && is_callable($$act)) $$act();
}

function urlname($name){
	$name = preg_replace('/\W+/',' ',strtolower($name));
	$name = preg_replace('/\s+/','-',$name);
	return $name;
}

function shortname($name){
	return preg_replace('/-/','',urlname($name));
}
