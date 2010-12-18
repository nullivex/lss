<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'create':
		require_once(ROOT.'/ctl/admin/ad_create.php');
		break;

	case 'manage':
		require_once(ROOT.'/ctl/admin/ad_manage.php');
		break;

	default:
	case 'list':
		require_once(ROOT.'/ctl/admin/ad_list.php');
		break;

}