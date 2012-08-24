<?php

function gfa($data){
	$args = func_get_args();
	array_shift($args);
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
	if($output && !defined('OUT_QUIET')) echo $msg."\n"; flush();
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

function mirror($opts,&$usrdef){
	if(gfa($opts,'mirror')) $mirror = gfa($opts,'mirror');
	elseif(gfa($opts,'m')) $mirror = gfa($opts,'m');
	elseif(gfa($usrdef->data,'mirror')) $mirror = gfa($usrdef->data,'mirror');
	else $mirror = DEFAULT_MIRROR;
	define('MIRROR',$mirror);
}

function target($opts,&$usrdef){
	if(gfa($opts,'target')) $target = gfa($opts,'target');
	elseif(gfa($opts,'t')) $target = gfa($opts,'t');
	elseif(gfa($usrdef->data,'target')) $target = gfa($usrdef->data,'target');
	else $target = DEFAULT_TARGET;
	define('TARGET',$target);
}

function cache(&$usrdef){
	if(gfa($usrdef->data,'cache')) $cache = gfa($usrdef->data,'cache');
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

function prompt_confirm($prompt=":"){
	print $prompt;
	@flush();
	@ob_flush();
	$confirmation = trim(fgets(STDIN));
	if($confirmation !== 'y') return false;
	return true;
}

function prompt_silent($prompt=":"){
    $command = "/usr/bin/env bash -c 'echo OK'";
    if (rtrim(shell_exec($command)) !== 'OK') {
		trigger_error("Can't invoke bash");
		return;
    }
    $command = "/usr/bin/env bash -c 'read -s -p \""
		. addslashes($prompt)
		. "\" mypassword && echo \$mypassword'";
    $val = rtrim(shell_exec($command));
    echo "\n";
    return $val;
}

function exec_hook($hook_file,$act){
	include($hook_file);
	if(function_exists($act)) call_user_func($act);
}
