<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/ads.php');

try {
	$ads = Ads::_get();
	$ad = $ads->get(get('ad_id'));

	//update hits
	$ads->hit(get('ad_id'),server('REMOTE_ADDR'));

	//out
	redirect($ad['url']);
	ob_end_flush();
} catch (Exception $e){
	sysError($e->getMessage());
}

