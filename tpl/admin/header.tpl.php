<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
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
