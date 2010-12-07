<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	default:
	case 'manage':
		require_once(ROOT.'/ctl/seed_manage.php');
		break;

}