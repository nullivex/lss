<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'play':
		require_once(ROOT.'/ctl/game_play.php');
		break;
		
	case 'info':
		require_once(ROOT.'/ctl/game_info.php');
		break;

	case 'info_short':
		require_once(ROOT.'/ctl/game_info_short.php');
		break;
	
	default:
	case 'list':
		require_once(ROOT.'/ctl/game_list.php');
		break;

}
