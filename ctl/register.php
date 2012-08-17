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

if(post('register')){
	try {
		Members::_get()->register(post());
		alert("Registered successfully",true,true);
		redirect(Url::login());
	} catch(Exception $e){
		alert($e->getMessage(),false);
	}
}
Tpl::_get()->setConstant('site_title','Register for an Account'.SITE_TITLE);
Tpl::_get()->parse('register','register');

page_footer();
output(Tpl::_get()->output());
