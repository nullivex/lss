<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/pages.php');
page_header();

if(post('copy')){
	try {
		Pages::_get()->create(post());
		alert('page copied successfully',true,true);
		redirect(Url::pages());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('edit')){
	try {
		Pages::_get()->edit(post());
		alert('page updated successfully',true,true);
		redirect(Url::pages());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Pages::_get()->delete(post());
		alert('page deleted successfully',true,true);
		redirect(Url::pages());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Pages::_get()->editParams(get('page_id'));
$params = array_merge($params,post());
$params['content'] = htmlentities($params['content']);
$params['url_page_manage'] = Url::page_manage(get('page_id'));
Tpl::_get()->parse('pages','edit',$params);

page_footer();
output(Tpl::_get()->output());
