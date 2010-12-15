<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
require_once(ROOT.'/lib/categories.php');
page_header();

//show master category list
if(!get('category_id')){
	$list = Categories::_get()->categoryList();
	$categories = '';
	$i = 0;
	foreach($list as $category){
		$params = $category;
		$params['url'] = Url::game_list_category($category['category_id']);
		$params['row'] = $i % Config::get('list','category_row') == 0 ? '</tr><tr>' : ''; $i++;
		$categories .= Tpl::_get()->parse('game_list','category_row',$params,true);
	}
	$params = array();
	$params['categories'] = $categories;
	Tpl::_get()->parse('game_list','category_list',$params);
	page_footer();
	output(Tpl::_get()->output());
	exit;
}

$_category = Categories::_get()->get(get('category_id'));

//deal with paging
if(get('next')) get('page_no',get('page_no')+1);
if(get('prev')) get('page_no',get('page_no')-1);
if(get('page_no') > get('page_count')) get('page_no',get('page_count'));

$params = array();
$list = Games::_get()->categoryGames(get('category_id'),Config::get('list','game'),get('page_no'),get('sort'));
$i = 0; $games = '';
foreach($list as $game){
	$params = $game;
	if(!isset($page_count)) $page_count = ceil($game['game_count'] / Config::get('list','game'));
	$params['url'] = Url::game_info($game['game_id']);
	$params['row'] = $i % Config::get('list','game_row') == 0 ? '</tr><tr>' : ''; $i++;
	$games .= Tpl::_get()->parse('game_list','game_row',$params,true);
}

$params = array();
$params = array_merge(get(),$params);
$params['games'] = $games;
$params['page_no'] = get('page_no') ? get('page_no') : 1;
$params['page_count'] = $page_count;
$params['category'] = $_category['name'];
$params['url_category'] = Url::game_list_category($_category['category_id']);

//deal with sorting
$params['sort_name'] = preg_match('/name/',get('sort')) ? 'selected="selected"' : '';
$params['sort_plays'] = preg_match('/plays/',get('sort')) ? 'selected="selected"' : '';
$params['sort_created'] = preg_match('/created/',get('sort')) ? 'selected="selected"' : '';
$params['sort_last_played'] = preg_match('/last_played/',get('sort')) ? 'selected="selected"' : '';

Tpl::_get()->parse('game_list','game_list',$params);

page_footer();
output(Tpl::_get()->output());
