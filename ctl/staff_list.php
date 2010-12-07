<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/staff.php');

Login::requireManager();
page_header();

$list = Staff::_get()->staffList();
$html = '';
$count = 1;
foreach($list as $staff){
	$params = $staff;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['manager'] = $staff['is_manager'] ? 'Yes' : 'No';
	$params['url'] = Url::staff_manage($staff['staff_id']);
	$html .= Tpl::_get()->parse('staff','staff_row',$params,true);
	$count += 1;
}

$params = array();
$params['staff'] = $html;
Tpl::_get()->parse('staff','staff',$params);

page_footer();
output(Tpl::_get()->output());
