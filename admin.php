<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
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
define("ROOT",$config['paths']['sg']);

//load base libs
require_once(ROOT.'/src/func.php');
require_once(ROOT.'/lib/config.php');
require_once(ROOT.'/lib/db.php');
require_once(ROOT.'/lib/admin/login.php');
require_once(ROOT.'/lib/tpl.php');
require_once(ROOT.'/lib/admin/url.php');
require_once(ROOT.'/lib/validate.php');

//set constants
define("SG_VERSION","1.0.0");

try {

	//load config
	Config::_get()->setConfig($config);

	//load db connection
	Db::_get()->setConfig(Config::_get()->get('db'))->connect();

	//load tpl
	Tpl::_get()->setPath(Config::_get()->get('tpl','admin_path'));
	Tpl::_get()->setThemePath(Config::_get()->get('tpl','admin_theme_path'));
	Tpl::_get()->initConstants();
	Tpl::_get()->setConstant('sg_version',SG_VERSION);

	//do the login requirements
	Login::_get(Config::_get()->get('root_user'))->check();
	Tpl::_get()->setConstant('user_username',Login::$user['username']);

	//setup admin nav
	Tpl::_get()->adminNav(Login::$user);

	switch(get('act')){

		case 'pages':
			require_once(ROOT.'/router/admin/pages.php');
			break;
		
		case 'staff':
			require_once(ROOT.'/router/admin/staff.php');
			break;

		case 'profile':
			require_once(ROOT.'/router/admin/profile.php');
			break;
		
		case 'members':
			require_once(ROOT.'/router/admin/members.php');
			break;

		case 'categories':
			require_once(ROOT.'/router/admin/categories.php');
			break;
		
		case 'games':
			require_once(ROOT.'/router/admin/games.php');
			break;
		
		case 'traffic':
		default:
			require_once(ROOT.'/router/admin/traffic.php');
			break;

	}

} catch(Exception $e){
	sysError($e->getMessage());
}