<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$config = array();

//info
$config['info']['site_name'] = 'Sloppygames';
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
$config['paths']['sg'] = dirname(__FILE__);

//db
$config['db']['driver'] = 'mysql';
$config['db']['host'] = 'localhost';
$config['db']['port'] = '';
$config['db']['user'] = 'root';
$config['db']['password'] = '';
$config['db']['database'] = 'tacker';

//tpl
$config['tpl']['path'] = 'tpl';
$config['tpl']['theme_path'] = 'theme';

//api
$config['api']['enabled'] = false;
$config['api']['username'] = '';
$config['api']['password'] = '';
