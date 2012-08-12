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

$tpl = array();

$tpl['header'] = <<<HTML
<html>
<head>
<title>{site_name} - Admin Panel</title>
<link rel="stylesheet" type="text/css" href="{css}/main.css" />
</head>
<body>
	<div id="header_frame">
		<div class="banner_nav">
			<a href="{url_profile}">Profile</a>&nbsp;|&nbsp;
			<a href="{url_logout}">Logout ({user_username})</a>
		</div>
		<div id="site_name"><a href="{url_home}">{site_name}</a></div>
	</div>
	<div class="mainNavFrame">
		<div class="mainNav">
			{admin_nav}
		</div>
	</div>
<div class="content">
{alert}
HTML;
