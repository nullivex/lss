<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	default:
	case 'profile':
		require_once(ROOT.'/ctl/admin/staff_profile.php');
		break;

}
