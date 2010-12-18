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
	$words = explode('-',$game['urlname']);
	foreach($words as $word){
		if(empty($word)) continue;
		if(strlen($word) < Config::get('tags','min')) continue;
		if(in_array($word,Config::get('tags','skip'))) continue;
		$query = $db->prepare('select * from tags where tag = ?');
		$query->execute(array(shortname($word)));
		$tag = $query->fetch(); $query->closeCursor();
		if(!(isset($tag['tag_id']) && !empty($tag['tag_id']))){
			$query = $db->prepare('insert into tags (tag,size)values(?,?)');
			$query->execute(array($word,0));
			$tag_id = $db->lastInsertId();
		} else {
			$tag_id = $tag['tag_id'];
		}
		$query = $db->prepare('delete from game_tags where tag_id = ? and game_id = ?');
		$query->execute(array($tag_id,$game['game_id']));
		$query = $db->prepare('insert into game_tags (tag_id,game_id)values(?,?)');
		$query->execute(array($tag_id,$game['game_id']));
	}
}