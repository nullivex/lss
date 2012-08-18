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
include_once('config.defaults.php');
include_once('config.php');

//set timezone
date_default_timezone_set($config['info']['default_timezone']);

//set root path
define("ROOT",$config['paths']['lss']);

//load base libs
require_once(ROOT.'/src/func.php');
require_once(ROOT.'/lib/config.php');
require_once(ROOT.'/lib/db.php');
require_once(ROOT.'/lib/admin/login.php');
require_once(ROOT.'/lib/tpl.php');
require_once(ROOT.'/lib/admin/url.php');
require_once(ROOT.'/lib/validate.php');

try {

	//load config
	Config::_get()->setConfig($config);

	//load db connection
	Db::_get()->setConfig(Config::_get()->get('db'))->connect();

	//load tpl
	Tpl::_get()->setPath(Config::_get()->get('tpl','admin_path'));
	Tpl::_get()->setThemePath(Config::_get()->get('tpl','admin_theme_path'));
	Tpl::_get()->initConstants();
	Tpl::_get()->setConstant('lss_version',LSS_VERSION);

	//do the login requirements
	Login::_get(Config::_get()->get('root_user'))->check()->adminNav(Login::$user);
	Tpl::_get()->setConstant('user_username',Login::$user['username']);

	switch(get('act')){
		
		case 'staff':
			require_once(ROOT.'/rtr/admin/staff.php');
			break;

		case 'profile':
			require_once(ROOT.'/rtr/admin/profile.php');
			break;
		
		case 'members':
			require_once(ROOT.'/rtr/admin/members.php');
			break;
		
		case 'pages':
		default:
			require_once(ROOT.'/rtr/admin/pages.php');
			break;

	}

} catch(Exception $e){
	sysError($e->getMessage());
}
