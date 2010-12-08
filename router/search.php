<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	default:
	case 'search':
		require_once(ROOT.'/ctl/search.php');
		break;

}
