<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/pages.php');
page_header();

$list = Pages::_get()->pageList();
$html = '';
$count = 1;
foreach($list as $page){
	$params = $page;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['url'] = Url::page_manage($page['page_id']);
	$html .= Tpl::_get()->parse('pages','page_row',$params,true);
	$count += 1;
}

$params = array();
$params['pages'] = $html;
Tpl::_get()->parse('pages','pages',$params);

page_footer();
output(Tpl::_get()->output());
