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
$config['db']['database'] = 'sg';

//tpl
$config['tpl']['path'] = 'tpl';
$config['tpl']['admin_path'] = 'tpl/admin';
$config['tpl']['theme_path'] = 'theme';
$config['tpl']['admin_theme_path'] = 'theme/admin';

//api
$config['api']['enabled'] = false;
$config['api']['username'] = '';
$config['api']['password'] = '';

//crawler
$config['crawler']['enabled'] = false;
$config['crawler']['publisher_id'] = '';
$config['crawler']['base_url'] = 'http://www.mochimedia.com/feeds/games/[[pub]]/all/all';
$config['crawler']['chunk'] = 10; //games
$config['crawler']['advance'] = 7; //games
$config['crawler']['throttle'] = 200; //ms
$config['crawler']['sleep'] = 1; //secs
$config['crawler']['verbose'] = true; //show output