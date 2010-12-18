<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
require_once(ROOT.'/lib/tags.php');
page_header();

$games = Games::_get();
$tags = Tags::_get();
$params = array();

//define lists
$params['featured_games'] = $params['featured_nav'] = $params['most_played_games'] ='';
$params['recently_played_games'] = $params['random_games'] = $params['recently_added_games'] = $params['tags'] = '';

//populate lists
foreach($games->gamesFeatured(Config::get('list','home_featured')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$params['featured_games'] .= Tpl::_get()->parse('home','featured_row',$game,true);
	$params['featured_nav'] .= Tpl::_get()->parse('home','featured_nav_row',$game,true);
}

$i = 0;
foreach($games->gamesMostPlayed(Config::get('list','home_most_played')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$game['row'] = $i % Config::get('list','home_most_played_row') == 0 ? '</tr><tr>' : ''; $i++;
	$params['most_played_games'] .= Tpl::_get()->parse('home','most_played_row',$game,true);
}

$i = 0;
foreach($games->gamesRecentlyPlayed(Config::get('list','home_recently_played')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$game['row'] = $i % Config::get('list','home_recently_played_row') == 0 ? '</tr><tr>' : ''; $i++;
	$params['recently_played_games'] .= Tpl::_get()->parse('home','recently_played_row',$game,true);
}

$i = 0;
foreach($games->gamesRandom(Config::get('list','home_random')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$game['row'] = $i % Config::get('list','home_random_row') == 0 ? '</tr><tr>' : ''; $i++;
	$params['random_games'] .= Tpl::_get()->parse('home','random_row',$game,true);
}

$i = 0;
foreach($games->gamesRecentlyAdded(Config::get('list','home_random')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$game['row'] = $i % Config::get('list','home_recently_added_row') == 0 ? '</tr><tr>' : ''; $i++;
	$params['recently_added_games'] .= Tpl::_get()->parse('home','recently_added_row',$game,true);
}

foreach($tags->randomCloud(20) as $tag){
	$tag['url'] = Url::tag($tag['tag_id']);
	$params['tags'] .= Tpl::_get()->parse('home','tag_link',$tag,true);
}

Tpl::_get()->parse('home','home',$params);

page_footer();
output(Tpl::_get()->output());
