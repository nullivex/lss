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

//load config
chdir(dirname(__FILE__));
include_once('../config.defaults.php');
include_once('../config.php');

//set timezone
date_default_timezone_set($config['info']['default_timezone']);

//set root path
define("ROOT",$config['paths']['lss']);

//load base libs
require_once(ROOT.'/src/func.php');
require_once(ROOT.'/src/api.php');
require_once(ROOT.'/lib/config.php');
require_once(ROOT.'/lib/db.php');
require_once(ROOT.'/lib/tpl.php');
require_once(ROOT.'/lib/validate.php');

//set constants
define("LSS_VERSION","0.1.0");

try {

	//load config
	Config::_get()->setConfig($config);

	//api auth
	if(server('DOCUMENT_ROOT')){
		list($username,$password) = apiCreds($_POST);
		apiAuth($username,$password);
	}

	//load db connection
	Db::_get()->setConfig(Config::_get()->get('db'))->connect();

	//load tpl
	Tpl::_get()->setPath(Config::_get()->get('tpl','path'));
	Tpl::_get()->setThemePath(Config::_get()->get('tpl','theme_path'));
	Tpl::_get()->initConstants();
	Tpl::_get()->setConstant('LSS_version',LSS_VERSION);


} catch (Exception $e){
	apiError($e->getMessage());
}

//api code will start now