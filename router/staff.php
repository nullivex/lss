<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'create':
		require_once(ROOT.'/ctl/staff_create.php');
		break;

	case 'manage':
		require_once(ROOT.'/ctl/staff_manage.php');
		break;

	default:
	case 'list':
		require_once(ROOT.'/ctl/staff_list.php');
		break;

}