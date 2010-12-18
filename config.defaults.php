<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
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
$config['crawler']['chunk'] = 500; //games
$config['crawler']['advance'] = 495; //games
$config['crawler']['throttle'] = 2000; //ms
$config['crawler']['sleep'] = 5; //secs
$config['crawler']['verbose'] = true; //show output

//list
$config['list']['home_featured'] = 4;
$config['list']['home_most_played'] = 12;
$config['list']['home_most_played_row'] = 3;
$config['list']['home_recently_played'] = 8;
$config['list']['home_random'] = 8;
$config['list']['home_recently_added'] = 8;
$config['list']['home_recently_played_row'] = 2;
$config['list']['home_random_row'] = 2;
$config['list']['home_recently_added_row'] = 2;
$config['list']['info_featured'] = 12;
$config['list']['info_featured_row'] = 4;
$config['list']['info_related'] = 12;
$config['list']['info_related_row'] = 4;
$config['list']['play_featured'] = 10;
$config['list']['play_featured_row'] = 5;
$config['list']['play_related'] = 6;
$config['list']['play_related_row'] = 3;
$config['list']['category_row'] = 4;
$config['list']['game_row'] = 4;
$config['list']['game'] = 32;
$config['list']['search'] = 12;
$config['list']['search_row'] = 6;
$config['list']['search_all'] = 36;

//game
$config['game']['fullpage_min_width'] = 780;

//tags
$config['tags']['min'] = 3;
$config['tags']['skip'] = array('a','and','the','in','a','you','i');
$config['tags']['size_min'] = 8;
$config['tags']['size_max'] = 22;