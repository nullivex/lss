<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/categories.php');
page_header();

if(post('create')){
	try {
		Categories::_get()->create(post());
		alert('category added successfully',true,true);
		redirect(Url::categories());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Categories::_get()->createParams();
$params = array_merge($params,post());
Tpl::_get()->parse('categories','create',$params);

page_footer();
output(Tpl::_get()->output());
