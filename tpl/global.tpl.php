<?php
/*
 * Tacker - Simple task tracker system
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
	Admin:
	<a href="{url_manage}">{manage_mode_action}</a> |
	<a href="{url_task_review}">Review Tasks</a> |
	<a href="{url_task_reports}">Task Reports</a> |
	<a href="{url_task_roadmap}">Roadmap</a> |
	<a href="{url_seed}">Seeds</a> | 
	<a href="{url_staff}">Manage Staff</a>
</div>
HTML;
