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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">
<title>{site_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="cache-control" content="no-cache" />
<link rel="stylesheet" type="text/css" href="{css}/style.css" media="screen" />
</head>
<body>
<div id="header">
	<h1><a href="{url_home}">{site_name}</a></h1>
	{member_bar}
</div>
<div id="wrapper">
{alert}
HTML;

$tpl['member_bar'] = <<<HTML
<div>
	Welcome <strong>{user_username}</strong>
	<a href="{url_profile}">Profile</a> | <a href="{url_logout}">Logout</a>
</div>
HTML;

$tpl['login_bar'] = <<<HTML
<div><a href="{url_login}">Login</a> | <a href="{url_register}">Register</a></div>
HTML;
