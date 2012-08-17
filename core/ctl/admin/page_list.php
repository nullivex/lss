<?php
/*
 * LSS Core
 * OpenLSS - Light, sturdy, stupid simple
 * 2010 Nullivex LLC, All Rights Reserved.
 * Bryan Tong <contact@nullivex.com>
 *
 *   OpenLSS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   OpenLSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenLSS.  If not, see <http://www.gnu.org/licenses/>.
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
