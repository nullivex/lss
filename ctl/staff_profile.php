<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/staff.php');

page_header();

if(post('edit')){
	try {
		Staff::_get()->profile($_POST);
		alert('staff profile updated successfully',true,true);
		redirect(Url::profile());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Staff::_get()->editParams(Login::staffId());
$params = array_merge($params,$_POST);
Tpl::_get()->parse('staff','profile',$params);

page_footer();
output(Tpl::_get()->output());
