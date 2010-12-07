<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/staff.php');

Login::requireManager();
page_header();

if(post('create')){
	try {
		Staff::_get()->create($_POST);
		alert('staff member added successfully',true,true);
		redirect(Url::staff());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Staff::_get()->createParams();
$params = array_merge($params,$_POST);
$params['is_manager'] = post('is_manager') ? 'checked="checked"' : '';
Tpl::_get()->parse('staff','create',$params);

page_footer();
output(Tpl::_get()->output());
