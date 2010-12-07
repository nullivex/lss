<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/members.php');
page_header();

$list = Members::_get()->memberList();
$html = '';
$count = 1;
foreach($list as $member){
	$params = $member;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['affiliate'] = $member['is_affiliate'] ? 'Yes' : 'No';
	$params['url'] = Url::member_manage($member['member_id']);
	$html .= Tpl::_get()->parse('members','member_row',$params,true);
	$count += 1;
}

$params = array();
$params['members'] = $html;
Tpl::_get()->parse('members','members',$params);

page_footer();
output(Tpl::_get()->output());
