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

require_once(ROOT.'/lib/members.php');
page_header();

if(post('edit')){
	try {
		Members::_get()->edit(post());
		alert('member updated successfully',true,true);
		redirect(Url::members());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Members::_get()->delete(post());
		alert('member deleted successfully',true,true);
		redirect(Url::members());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Members::_get()->editParams(get('member_id'));
$params = array_merge($params,post());
$params['is_affiliate'] = $params['is_affiliate'] ? 'checked="checked"' : '';
$params['url_member_manage'] = Url::member_manage(get('member_id'));
Tpl::_get()->parse('members','edit',$params);

page_footer();
output(Tpl::_get()->output());
