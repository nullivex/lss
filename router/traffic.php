<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'stats':
		require_once(ROOT.'/ctl/traffic_stats.php');
		break;

	default:	
	case 'member':
		require_once(ROOT.'/ctl/traffic_member.php');
		break;


}
