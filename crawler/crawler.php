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

if(!Config::get('crawler','enabled')) die('not enabled');
$pub = Config::get('crawler','publisher_id');
$url = Config::get('crawler','base_url');
$chunk = Config::get('crawler','chunk');
$advance = Config::get('crawler','advance');
$throttle = Config::get('crawler','throttle');
$sleep = Config::get('crawler','sleep');
$verbose = Config::get('crawler','verbose');

$do_crawl = true;
$seed = 0;
while($do_crawl){
	Db::_get()->setConfig(Config::_get()->get('db'))->connect();
	if($verbose) crawlerOutput('starting batch seed: '.$seed.' chunk: '.$chunk.' advance: '.$advance);
	$list = Crawler::_get()->gameList($pub,$url,$seed,$chunk,$advance,$do_crawl);
	if($list === false) break;
	foreach($list as $game){
		try {
			$category_id = Crawler::_get()->category($game);
			$data = Crawler::_get()->gameData($game,$category_id);
			if(Crawler::_get()->checkGame(shortname($data['name']))) continue;
			$data['media'] = Crawler::_get()->downloadGame($data['media'],shortname($data['name']));
			$data['icon'] = $data['large'] = $data['thumb'] = Crawler::_get()->downloadThumb($data['icon'],shortname($data['name']));
			$game_id = Games::_get()->create($data);
			if($game_id && $verbose) crawlerOutput('added '.$data['name'].' with id: '.$game_id);
		} catch(Exception $e){
			if($verbose) crawlerOutput($e->getMessage());
		}
		if($verbose) crawlerOutput('finished game... throttle: '.$throttle.'ms');
		usleep($throttle);
	}
	if($verbose) crawlerOutput('finished batch... sleeping: '.$sleep.'s');
	Db::_get()->close();
	sleep($sleep);
}
