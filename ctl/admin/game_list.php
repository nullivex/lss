<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

if(get('keywords')){
	$list = Games::_get()->gameSearch(get('keywords'),get('status'));
} else {
	$list = Games::_get()->gameList(get('status'),false,25);
}
$html = '';
$count = 1;
foreach($list as $game){
	$params = $game;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['featured'] = $game['is_featured'] ? 'Yes' : 'No';
	$params['tradable'] = $game['is_tradable'] ? 'Yes' : 'No';
	$params['active'] = $game['is_active'] ? 'Yes' : 'No';
	$params['url'] = Url::game_manage($game['game_id']);
	$html .= Tpl::_get()->parse('games','game_row',$params,true);
	$count += 1;
}

$params = array();
$params['games'] = $html;
$params['url_active'] = Url::game_status('active');
$params['url_tradable'] = Url::game_status('tradable');
$params['url_intradable'] = Url::game_status('intradable');
$params['url_featured'] = Url::game_status('featured');
$params['url_inactive'] = Url::game_status('inactive');
$params['url_deleted'] = Url::game_status('deleted');
$params['status'] = get('status') ? get('status') : 'active';
$params['keywords'] = htmlentities(get('keywords'));
Tpl::_get()->parse('games','games',$params);

page_footer();
output(Tpl::_get()->output());
