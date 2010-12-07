<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/tasks.php');

Login::requireManager();
page_header();

$params = Tasks::_get()->reports();
Tpl::_get()->parse('tasks','task_reports_hdr_general',$params);
Tpl::_get()->parse('tasks','task_reports_common',$params);

foreach(Login::$staff as $staff){
	$params = array_merge($staff,Tasks::_get()->reports($staff['staff_id']));
	Tpl::_get()->parse('tasks','task_reports_hdr_staff',$params);
	Tpl::_get()->parse('tasks','task_reports_common',$params);
}

page_footer();
output(Tpl::_get()->output());
