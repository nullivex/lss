<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

$list = Games::_get()->gameList();
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
Tpl::_get()->parse('games','games',$params);

page_footer();
output(Tpl::_get()->output());
