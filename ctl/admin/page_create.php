<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/pages.php');
page_header();

if(post('create')){
	try {
		Pages::_get()->create(post());
		alert('page added successfully',true,true);
		redirect(Url::pages());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Pages::_get()->createParams();
$params = array_merge($params,post());
Tpl::_get()->parse('pages','create',$params);

page_footer();
output(Tpl::_get()->output());
