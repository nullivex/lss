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

$tpl['main_menu'] = <<<HTML
<div id="column1">
	<div class="mlmenu vertical blindv delay inaccesible">
		<ul>
			{main_menu_categories}
		</ul>
	</div> <!--end: menu-->
	<div class="leftsidebar">
	{ad_home_left_160x600}
	</div>
</div>
HTML;

$tpl['main_menu_category_row'] = <<<HTML
<li class="haschild hide"><a href="{url}" title="{name}">{name}</a>
HTML;
