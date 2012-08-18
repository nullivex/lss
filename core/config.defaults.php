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

//set constants
define("LSS_VERSION","0.1.1");

$config = array();

//info
$config['info']['site_name'] = 'OpenLSS Core';
$config['info']['default_timezone'] = 'America/Chicago';

//date
$config['date']['start_format'] = 'D m-d g:i A';
$config['date']['end_format'] = 'D m-d g:i A';
$config['date']['review_format'] = 'Y-m-d G:i:s';

//authentication setup
$config['root_user'] = 'admin';

//url
$config['url']['url'] = '';
$config['url']['uri'] = '';

//paths
$config['paths']['lss'] = dirname(__FILE__);

//db
$config['db']['driver'] = 'mysql';
$config['db']['host'] = 'localhost';
$config['db']['port'] = '';
$config['db']['user'] = 'root';
$config['db']['password'] = '';
$config['db']['database'] = 'lss';

//tpl
$config['tpl']['path'] = 'tpl';
$config['tpl']['admin_path'] = 'tpl/admin';
$config['tpl']['theme_path'] = 'theme';
$config['tpl']['admin_theme_path'] = 'theme/admin';

//api
$config['api']['enabled'] = false;
$config['api']['username'] = '';
$config['api']['password'] = '';
