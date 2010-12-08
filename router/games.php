<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'play':
		require_once(ROOT.'/ctl/game_play.php');
		break;
		
	case 'info':
		require_once(ROOT.'/ctl/game_info.php');
		break;
	
	default:
	case 'list':
		require_once(ROOT.'/ctl/game_list.php');
		break;

}
