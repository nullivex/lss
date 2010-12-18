<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/ads.php');
page_header();

$list = Ads::_get()->adList();
$html = '';
$count = 1;
foreach($list as $ad){
	$params = $ad;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['url'] = Url::ad_manage($ad['ad_id']);
	$params['ad_url'] = $ad['url'];
	$html .= Tpl::_get()->parse('ads','ad_row',$params,true);
	$count += 1;
}

$params = array();
$params['ads'] = $html;
Tpl::_get()->parse('ads','ads',$params);

page_footer();
output(Tpl::_get()->output());
