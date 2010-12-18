<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['header'] = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">

<title>{site_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="cache-control" content="no-cache" />
<meta name="keywords" content="php, framework, light, sturdy, simple, stupid, powerful, flexible, easy" />
<meta name="description" content="PHP Framework done with a new way of thinking, a new level of simplicity." />
<link rel="stylesheet" type="text/css" href="{css}/game.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{css}/style.css" media="screen" />
<script language="javascript" type="text/javascript" src="{js}/ajaxtabs.js"></script>
<script type="text/javascript" src="{js}/menu.js"></script>
<script type="text/javascript" src="{js}/jquery.js"></script>
<script type="text/javascript" src="{js}/jquery-ui.js"></script>

<!--[if lt IE 7]>
<script type="text/javascript" src="http://www.theme-junkie.com/demo/portal/wp-content/themes/portal/javascripts/unitpngfix.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.theme-junkie.com/demo/portal/wp-content/themes/portal/ie.css" />
<![endif]-->

</head>
<body>

<div id="header">
	<div class="left">
		<a href="{url_home}" class="imagelogo"></a>
		<div class="clear"></div>
	</div>
</div>
<div id="headline">
	<div class="left">
		<span class="home-icon"><a href="{url_home}" title="Home">Home</a></span>
	</div>
	<div class="right">
		<div class="mlmenu horizontal fade inaccesible">
			<ul>
				<li class="page_item"><a href="{url_page}about" title="About {site_name}">About</a></li>
				<li class="page_item"><a href="{url_page}download" title="Download {site_name}">Download</a></li>
				<li class="page_item"><a href="http://wiki.openlss.com" title="{site_name} Wiki">Wiki</a></li>
				<li class="page_item"><a href="http://bugs.openlss.com" title="{site_name} Bugs">Bugs</a></li>
			</ul>
		</div> <!--end: menu-->
	</div> <!--end: right-->
	<div class="clear"></div>
</div> <!--headline-->

<div id="wrapper">
{alert}
HTML;

