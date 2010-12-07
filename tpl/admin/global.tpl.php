<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['redirect'] = <<<HTML
<html>
	<head>
		<title>Redirecting Page - {site_name}</title>
		<meta http-equiv="refresh" content="{time};url={url}" />
		<link rel="stylesheet" type="text/css" href="{css}/main.css" />
	</head>
	<body>
		<div class="redirection">
			<h1>Redirecting Page - {site_name}</h1>
			<div>If you are not redirected <a href="{url}">Click Here</a></div><br />
		</div>
	</body>
</html>
HTML;

$tpl['alert'] = <<<HTML
	<div class="alert {class}">
		<p>{message}</p>
	</div>
HTML;

$tpl['select'] = <<<HTML
<select name="{name}">
{options}
</select>
HTML;

$tpl['select_option'] = <<<HTML
	<option value="{value}" {checked}>{desc}</option>
HTML;

$tpl['admin_nav'] = <<<HTML
<div class='nav'>
	<a href="{url_traffic}">Traffic</a> |
	<a href="{url_categories}">Categories</a> |
	<a href="{url_games}">Games</a> |
	<a href="{url_pages}">Pages</a> |
	<a href="{url_members}">Members</a> |
	<a href="{url_staff}">Staff</a>
</div>
HTML;

$tpl['staff_nav'] = <<<HTML
<div class='nav'>
	<a href="{url_traffic}">Traffic</a> |
	<a href="{url_categories}">Categories</a> |
	<a href="{url_games}">Games</a> |
	<a href="{url_pages}">Pages</a> | 
	<a href="{url_members}">Members</a>
</div>
HTML;
