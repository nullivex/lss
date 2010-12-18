<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'register':
		require_once(ROOT.'/ctl/register.php');
		break;

	default:	
	case 'login':
		require_once(ROOT.'/ctl/login.php');
		break;


}
