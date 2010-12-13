<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/ads.php');
page_header();

if(post('create')){
	try {
		Ads::_get()->create(post());
		alert('ad added successfully',true,true);
		redirect(Url::ads());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Ad::_get()->createParams();
$params = array_merge($params,post());
Tpl::_get()->parse('ads','create',$params);

page_footer();
output(Tpl::_get()->output());
