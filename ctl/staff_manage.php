<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/staff.php');

Login::requireManager();
page_header();

if(post('edit')){
	try {
		Staff::_get()->edit($_POST);
		alert('staff updated successfully',true,true);
		redirect(Url::staff());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Staff::_get()->delete($_POST);
		alert('staff deleted successfully',true,true);
		redirect(Url::staff());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Staff::_get()->editParams(get('staff_id'));
$params = array_merge($params,$_POST);
$params['is_manager'] = $params['is_manager'] ? 'checked="checked"' : '';
$params['url_staff_manage'] = Url::staff_manage(get('staff_id'));
Tpl::_get()->parse('staff','edit',$params);

page_footer();
output(Tpl::_get()->output());
