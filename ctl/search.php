<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

//deal with paging
if(get('next')) get('page_no',get('page_no')+1);
if(get('prev')) get('page_no',get('page_no')-1);
if(get('page_no') > get('page_count')) get('page_no',get('page_count'));

$limit = get('do') == 'all' ? Config::get('list','search_all') : Config::get('list','search');
list($count,$list) = Games::_get()->search(get('q'),$limit,get('page_no'));
$page_count = ceil($count / $limit);

$games = ''; $i = 0;
foreach($list as $game){
	$params = $game;
	$params['row'] = $i % Config::get('list','search_row') == 0? '</tr><tr>' : ''; $i++;
	$games .= Tpl::_get()->parse('search','game_row',$params,true);
}



$params = array();
$params['games'] = $games;
$params['phrase'] = get('q');
$params['results'] = $count;
$params['url_all'] = Url::search_all(get('q'));
$params['page_no'] = get('page_no') ? get('page_no') : 1;
$params['page_count'] = $page_count;
$tpl = get('do') == 'all' ? 'search_full' : 'search';
Tpl::_get()->parse('search',$tpl,$params);

page_footer();
output(Tpl::_get()->output());
