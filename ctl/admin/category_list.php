<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/categories.php');
page_header();

$list = Categories::_get()->categoryList();
$html = '';
$count = 1;
foreach($list as $category){
	$params = $category;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['url'] = Url::category_manage($category['category_id']);
	$html .= Tpl::_get()->parse('categories','category_row',$params,true);
	$count += 1;
}

$params = array();
$params['categories'] = $html;
Tpl::_get()->parse('categories','categories',$params);

page_footer();
output(Tpl::_get()->output());
