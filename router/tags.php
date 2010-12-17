<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	default:
	case 'view':
		require_once(ROOT.'/ctl/tag_view.php');
		break;

}
