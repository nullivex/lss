<?php
/*
 * LSS Core
 * OpenLSS - Light, sturdy, stupid simple
 * 2010 Nullivex LLC, All Rights Reserved.
 * Bryan Tong <contact@nullivex.com>
 *
 *   OpenLSS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   OpenLSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenLSS.  If not, see <http://www.gnu.org/licenses/>.
 */

ob_start();
session_start();
define('START',microtime(true));

//load config
$config = array();
$dir = opendir('conf.d');
if($dir){
	while($dir && ($file = readdir($dir)) !== false){
		if(in_array($file,array('.','..'))) continue;
		if(is_dir($file)) continue;
		include('conf.d/'.$file);
	}
	closedir($dir);
}
$dir = opendir('conf.d/admin');
if($dir){
	while($dir && ($file = readdir($dir)) !== false){
		if(in_array($file,array('.','..'))) continue;
		if(is_dir($file)) continue;
		include('conf.d/admin/'.$file);
	}
	closedir($dir);
}
@include_once('config.php');

//set timezone
date_default_timezone_set($config['info']['default_timezone']);

//set root path
define("ROOT",$config['paths']['lss']);

//load global funcs
require_once(ROOT.'/src/func.php');
require_once(ROOT.'/lib/router.php');
require_once(ROOT.'/lib/admin/url.php');

try {

	//init modules
	$dir = opendir('init.d');
	if($dir){
		while($dir && ($file = readdir($dir)) !== false){
			if(in_array($file,array('.','..'))) continue;
			if(is_dir($file)) continue;
			include('init.d/'.$file);
		}
		closedir($dir);
	}
	$dir = opendir('init.d/admin');
	if($dir){
		while($dir && ($file = readdir($dir)) !== false){
			if(in_array($file,array('.','..'))) continue;
			if(is_dir($file)) continue;
			include('init.d/admin/'.$file);
		}
		closedir($dir);
	}
	
	//router
	Router::init();
	Router::_get()->setDefault('ctl/admin/home.php');
	$dir = opendir('rtr.d/admin');
	if($dir){
		while(($file = readdir($dir)) !== false){
			if(in_array($file,array('.','..'))) continue;
			if(is_dir($file)) continue;
			include('rtr.d/admin/'.$file);
		}
		closedir($dir);
	}
	$ctl = Router::_get()->route(req('act'),req('do'),req('fire'));
	require_once($ctl);

} catch(Exception $e){
	sysError($e->getMessage());
}
