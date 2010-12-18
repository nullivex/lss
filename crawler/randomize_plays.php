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
require_once(ROOT.'/lib/games.php');
require_once(ROOT.'/lib/categories.php');

Db::_get()->setConfig(Config::_get()->get('db'))->connect();

if(!Config::get('crawler','enabled')) die('not enabled');
$verbose = Config::get('crawler','verbose');

$games = Games::_get();
$db = Db::_get();
foreach($games->gameList('all') as $game){
	if($game['plays'] > 0) continue;
	$query = $db->prepare('update games set plays = ? where game_id = ?');
	$query->execute(array(mt_rand(0,10000),$game['game_id']));
}