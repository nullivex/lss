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
