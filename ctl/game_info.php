<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

$games = Games::_get();
$_game = $games->get(get('game_id'));
$params = $_game;

//urls
$params['url_category'] = Url::game_list_category($_game['category_id']);
$params['url_game'] = Url::game_info($_game['game_id']);
$params['url_game_play'] = Url::game_play($_game['game_id']);

//define section
$params['comments'] = $params['featured_games'] = $params['related_games'] = '';

//related games
$i = 0;
foreach($games->relatedGames($_game['game_id'],Config::get('list','info_related')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$game['row'] = $i % Config::get('list','info_related_row') == 0 ? '</tr><tr>' : ''; $i++;
	$params['related_games'] .= Tpl::_get()->parse('game_info','related_row',$game,true);
}

//featured games
$i = 0;
foreach($games->gamesFeatured(Config::get('list','info_featured')) as $game){
	$game['url'] = Url::game_info($game['game_id']);
	$game['row'] = $i % Config::get('list','info_featured_row') == 0 ? '</tr><tr>' : ''; $i++;
	$params['featured_games'] .= Tpl::_get()->parse('game_info','featured_row',$game,true);
}

Tpl::_get()->parse('game_info','game_info',$params);

page_footer();
output(Tpl::_get()->output());
