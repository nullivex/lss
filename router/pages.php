<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	default:
	case 'pages':
		require_once(ROOT.'/ctl/page_view.php');
		break;

}
