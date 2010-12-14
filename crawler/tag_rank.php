#!/usr/bin/php
<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

//setup
chdir(dirname(__FILE__));
require('crawler_common.php');
require_once(ROOT.'/lib/tags.php');

Db::_get()->setConfig(Config::_get()->get('db'))->connect();

if(!Config::get('crawler','enabled')) die('not enabled');
$verbose = Config::get('crawler','verbose');

$tags = Tags::_get();
$db = Db::_get();
foreach($tags->tagList() as $tag){
	$query = $db->prepare('select count(*) as amount from game_tags where tag_id = ?');
	$query->execute(array($tag['tag_id']));
	$result = $query->fetch(); $query->closeCursor();
	$tags->updateSize($tag['tag_id'],$result['amount']);
}