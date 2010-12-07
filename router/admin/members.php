<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'create':
		require_once(ROOT.'/ctl/admin/member_create.php');
		break;

	case 'manage':
		require_once(ROOT.'/ctl/admin/member_manage.php');
		break;

	default:
	case 'list':
		require_once(ROOT.'/ctl/admin/member_list.php');
		break;

}