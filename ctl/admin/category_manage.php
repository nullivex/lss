<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/categories.php');
page_header();

if(post('edit')){
	try {
		Categories::_get()->edit(post());
		alert('category updated successfully',true,true);
		redirect(Url::categories());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Categories::_get()->delete(post());
		alert('category deleted successfully',true,true);
		redirect(Url::categories());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Categories::_get()->editParams(get('category_id'));
$params = array_merge($params,post());
$params['categories'] = Categories::_get()->categoryDrop('new_category_id');
$params['url_category_manage'] = Url::category_manage(get('category_id'));
Tpl::_get()->parse('categories','edit',$params);

page_footer();
output(Tpl::_get()->output());
