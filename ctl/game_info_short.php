<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');

$games = Games::_get();
$_game = $games->get(get('game_id'));
$params = $_game;

switch(get('type')){
	case 'instructions':
		$tpl = 'short_instructions';
		break;
	case 'description':
	default:
		$tpl = 'short_description';
		break;
}

Tpl::_get()->parse('game_info',$tpl,$params);
output(Tpl::_get()->output());
