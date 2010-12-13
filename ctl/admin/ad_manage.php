<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/ads.php');
page_header();

if(post('copy')){
	try {
		Ads::_get()->create(post());
		alert('ad copied successfully',true,true);
		redirect(Url::ads());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('edit')){
	try {
		Ads::_get()->edit(post());
		alert('ad updated successfully',true,true);
		redirect(Url::ads());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Ads::_get()->delete(post());
		alert('ad deleted successfully',true,true);
		redirect(Url::ads());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Ads::_get()->editParams(get('ad_id'));
$params = array_merge($params,post());
$params['url_ad_manage'] = Url::ad_manage(get('ad_id'));
Tpl::_get()->parse('ads','edit',$params);

page_footer();
output(Tpl::_get()->output());
