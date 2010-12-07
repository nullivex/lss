<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/members.php');

page_header();

if(post('create')){
	try {
		Members::_get()->create(post());
		alert('member added successfully',true,true);
		redirect(Url::members());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Members::_get()->createParams();
$params = array_merge($params,post());
$params['is_affiliate'] = post('is_affiliate') ? 'checked="checked"' : '';
Tpl::_get()->parse('members','create',$params);

page_footer();
output(Tpl::_get()->output());
