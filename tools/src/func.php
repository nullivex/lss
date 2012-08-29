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

function target($opts){
	if(gfa($opts,'target')) $target = gfa($opts,'target');
	elseif(gfa($opts,'t')) $target = gfa($opts,'t');
	else $target = getFromDef('target');
	if(is_null($target)) $target = DEFAULT_TARGET;
	define('TARGET',$target);
}

function cache(){
	$cache = getFromDef('cache');
	if(is_null($cache)) $cache = DEFAULT_CACHE;
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

function dumpvar(){
	ob_start();
	call_user_func_array('var_dump',func_get_args());
	UI::out(ob_get_clean());
}

function getFromDef($var){
	if(is_object(TgtDef::$inst) && isset(TgtDef::_get()->data[$var])) return TgtDef::_get()->data[$var];
	if(is_object(UsrDef::$inst) && isset(UsrDef::_get()->data[$var])) return UsrDef::_get()->data[$var];
	if(is_object(LsDef::$inst) && isset(LsDef::_get()->data[$var])) return LsDef::_get()->data[$var];
	return null;
}

function getFromDefMerged($var){
	if(is_object(TgtDef::$inst)) return array_merge(TgtDef::_get()->get($var),UsrDef::_get()->get($var),LsDef::_get()->get($var));
	if(is_object(UsrDef::$inst)) return array_merge(UsrDef::_get()->get($var),LsDef::_get()->get($var));
	return LsDef::_get()->get($var);
}

function getStatusCode($headers){
	$status_code = false;
	if(is_scalar($headers))
		$headers = array($headers);
	if(is_array($headers))
		foreach($headers as $h){
			$m = array ();
			if(preg_match('/^HTTP\/\d\.\d\s+([0-9]{3})/',$h,$m)){
				$status_code = (int)$m[1];
				break;
			}
		}
	return $status_code;
}

//--------------------------
//Setting Functions
//--------------------------
function setValue($def,$name=null,$value=null){
	if(is_null($name)) throw new Exception('Name of value to set must be present');
	$def->iostate = $def::READWRITE;
	if(!isset($def->data[$name]) || is_array($def->data[$name])) throw new Exception('Cannot set the value of an array or the variable does not exist');
	$def->add(array($name=>$value));
	return true;
}

function addValue($def,$name=null,$value=null,$key=null){
	if(is_null($name)) throw new Exception('Name of value to add must be present');
	$def->iostate = $def::READWRITE;
	//prepare the array
	if(!isset($def->data[$name])) $def->data[$name] = array();
	if(!is_array($def->data[$name])) throw new Exception('Trying to add a value to a non array');
	//add value to the array
	if(!is_null($key))
		$def->data[$name][$key] = $value;
	else
		$def->data[$name][] = $value;
	return true;
}

function delValue($def,$name=null,$value=null,$key=null){
	if(is_null($name)) throw new Exception('Name of value to delete must be present');
	$def->iostate = $def::READWRITE;
	if(!isset($def->data[$name])) throw new Exception('Value array does not exist');
	if(is_null($key)){
		//remove keys from the array by value
		$keys = array_keys($def->data[$name],$value);
		if(!is_array($keys) || !count($keys)) throw new Exception('Value could not be found for removal');
		foreach($keys as $k) unset($def->data[$name][$k]);
	} else {
		//remove explicit key from the array
		if(!is_null(gfa($def->data[$name],$key)))
			unset($def->data[$name][$key]);
		else
			throw new Exception('Value could not be found for removal');
	}
	return true;
}

//--------------------------
//Utility Functions
//--------------------------
function intVersion($version){
	Ui::out(Pkg::v2i($version)."\n");
	return true;
}

function mirror_get_contents($mirror,$url,&$mirrorauth=null){
	if(is_null($mirrorauth)) $mirrorauth = getFromDefMerged('mirrorauth');
	$mirrorauth = gfa($mirrorauth,$mirror);
	if($mirrorauth){
		$buff = @file_get_contents($url,false
			,stream_context_create(array('http'=>
				array('header'=>"Authorization: Basic "
				.base64_encode($mirrorauth)))
			)
		);
	} else
		$buff = @file_get_contents($url);
	//verify we didnt have a problem
	if($buff === false){
		if(
			isset($http_response_header) && 
			is_array($http_response_header) && 
			getStatusCode($http_response_header) == 401
		)
			throw new Exception($mirror.' authorization failed: '.(($mirrorauth) ? ' (mirrorauth incorrect)' : ' (set mirrorauth)'),ERR_MIRROR_AUTH_FAILED);
		else
			throw new Exception($mirror.' is not a valid mirror',ERR_MIRROR_INVALID);
	}
	return $buff;
}
