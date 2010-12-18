<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

ob_start();
session_start();
define('START',microtime(true));

//load config
include_once('config.defaults.php');
include_once('config.php');

//set timezone
date_default_timezone_set($config['info']['default_timezone']);

//set root path
define("ROOT",$config['paths']['openlss']);

//load base libs
require_once(ROOT.'/src/func.php');
require_once(ROOT.'/lib/config.php');
require_once(ROOT.'/lib/db.php');
require_once(ROOT.'/lib/tpl.php');
require_once(ROOT.'/lib/url.php');
require_once(ROOT.'/lib/validate.php');

//set constants
define("OPENLSS_VERSION","1.0.0");

try {

	//load config
	Config::_get()->setConfig($config);

	//load db connection
	Db::_get()->setConfig(Config::_get()->get('db'))->connect();

	//load tpl
	Tpl::_get()->setPath(Config::_get()->get('tpl','path'));
	Tpl::_get()->setThemePath(Config::_get()->get('tpl','theme_path'));
	Tpl::_get()->initConstants();
	Tpl::_get()->setConstant('openlss_version',OPENLSS_VERSION);

	//title stuff
	define("SITE_TITLE",' | '.Config::get('info','site_name'));
	Tpl::_get()->setConstant('site_title',Config::get('info','site_name'));

	switch(get('act')){

		case 'pages':
			require_once(ROOT.'/router/pages.php');
			break;
		
		case 'home':
		default:
			require_once(ROOT.'/router/home.php');
			break;

	}

} catch(Exception $e){
	sysError($e->getMessage());
}
