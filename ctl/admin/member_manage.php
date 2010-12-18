<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/members.php');
page_header();

if(post('edit')){
	try {
		Members::_get()->edit(post());
		alert('member updated successfully',true,true);
		redirect(Url::members());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Members::_get()->delete(post());
		alert('member deleted successfully',true,true);
		redirect(Url::members());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Members::_get()->editParams(get('member_id'));
$params = array_merge($params,post());
$params['is_affiliate'] = $params['is_affiliate'] ? 'checked="checked"' : '';
$params['url_member_manage'] = Url::member_manage(get('member_id'));
Tpl::_get()->parse('members','edit',$params);

page_footer();
output(Tpl::_get()->output());
