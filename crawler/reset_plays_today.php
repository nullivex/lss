#!/usr/bin/php
<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

//setup
chdir(dirname(__FILE__));
require('crawler_common.php');

Db::_get()->setConfig(Config::_get()->get('db'))->connect();

if(!Config::get('crawler','enabled')) die('not enabled');
$verbose = Config::get('crawler','verbose');

$db = Db::_get();
$query = $db->prepare('update games set plays_today = 0');
$query->execute();

if($verbose) crawlerOutput("Game plays today reset");