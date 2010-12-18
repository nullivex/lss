<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'create':
		require_once(ROOT.'/ctl/admin/category_create.php');
		break;

	case 'manage':
		require_once(ROOT.'/ctl/admin/category_manage.php');
		break;

	default:
	case 'list':
		require_once(ROOT.'/ctl/admin/category_list.php');
		break;

}