<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	default:
	case 'profile':
		require_once(ROOT.'/ctl/staff_profile.php');
		break;

}