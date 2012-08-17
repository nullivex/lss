<?php

function gfa(){
	$args = func_get_args();
	$data = array_shift($args);
	$var = ''; foreach($args as $arg) $var .= '[\''.$arg.'\']';
	eval('$val = isset($data'.$var.') ? $data'.$var.' : null;');
	return $val;
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
