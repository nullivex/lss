<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$config = array();

//info
$config['info']['site_name'] = 'OpenLSS';
$config['info']['default_timezone'] = 'America/Chicago';

//authentication setup
$config['root_user'] = 'admin';

//url
$config['url']['url'] = '';
$config['url']['uri'] = '';

//paths
$config['paths']['openlss'] = dirname(__FILE__);

//db
$config['db']['driver'] = 'mysql';
$config['db']['host'] = 'localhost';
$config['db']['port'] = '';
$config['db']['user'] = 'openlss';
$config['db']['password'] = '';
$config['db']['database'] = 'openlss';

//tpl
$config['tpl']['path'] = 'tpl';
$config['tpl']['admin_path'] = 'tpl/admin';
$config['tpl']['theme_path'] = 'theme';
$config['tpl']['admin_theme_path'] = 'theme/admin';

//api
$config['api']['enabled'] = false;
$config['api']['username'] = '';
$config['api']['password'] = '';
