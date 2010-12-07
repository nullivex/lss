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
<title>{site_name}</title>
<link rel="stylesheet" type="text/css" href="{css}/main.css" />
</head>
<body>
	<div id="header_frame">
		<div class="banner_nav">
			<a href="{url_task_roadmap}">Roadmap</a>&nbsp;|&nbsp;
			<a href="{url_profile}">Profile</a>&nbsp;|&nbsp;
			<a href="{url_tasks}">Tasks</a>&nbsp;|&nbsp;
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

$tpl['header'] = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">

<title>Portal v1.0</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="cache-control" content="no-cache" />
<meta name="keywords" content="free games, free online games, games, online games, play games, racing games, car games, sports games, action games, adventure games, arcade games, shooting games, addicting games" />
<meta name="description" content="Play over 15000 FREE games here at SloppyGames.com, including free online games, arcade games, racing games, shooting games, and flash games!" />
<link rel="stylesheet" type="text/css" href="include/game.css" media="screen">
<link rel="stylesheet" type="text/css" href="include/style.css" media="screen">
<script language="javascript" type="text/javascript" src="include/ajaxtabs.js"></script>
<script type="text/javascript" src="include/menu.js"></script>
<script type="text/javascript" src="include/jquery.js"></script>
<script type="text/javascript" src="include/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 3500, true);
	});
</script>

<!--[if lt IE 7]>

<script type="text/javascript" src="http://www.theme-junkie.com/demo/portal/wp-content/themes/portal/javascripts/unitpngfix.js"></script>

<link rel="stylesheet" type="text/css" href="http://www.theme-junkie.com/demo/portal/wp-content/themes/portal/ie.css" />

<![endif]-->
</head><body>

	<div id="header">

		<div class="left">
							<a class="imagelogo" href="http://beta.sloppygames.com/"></a>
					</div> <!--end: left-->
		<div class="right">
			<div class="ad468x60">
	<a href="http://beta.sloppygames.com/" target="_blank"><img src="include/468x60.gif"></a></div> <!--end: ad468x60-->
		</div> <!--end: right-->
	</div> <!--end: header-->
<div id="headline">
	<div class="left">
		<span class="home-icon"><a href="http://beta.sloppygames.com/" title="Home">Home</a></span>
		<span class="login-icon"><a href="http://beta.sloppygames.com/login.php" title="Login">Login</a></span>
		<span class="register-icon"><a href="http://beta.sloppygames.com/register.php" title="Register">Register</a></span>
	</div> <!--end: left-->
	<div class="right">
		<div class="mlmenu horizontal fade inaccesible">
			<ul>
				<li class="page_item page-item-2"><a href="#" title="About">About</a></li>
<li class="page_item page-item-29"><a href="http://beta.sloppygames.com/elements.php" title="Elements">Elements</a></li>
<li class="page_item page-item-31haschild"><a href="http://beta.sloppygames.com/mediumpage.php" title="Page Templates">Page Templates</a>
<ul class="fade" style="z-index: 100; display: none; opacity: 0;">
	<li class="page_item category-1"><a href="http://beta.sloppygames.com/archives.php" title="Category Title">Archives</a></li>
	<li class="page_item category-2"><a href="http://beta.sloppygames.com/fullwidth.php" title="Category Title">Full Width</a></li>
	<li class="page_item category-3"><a href="http://www.theme-junkie.com/demo/portal/page-templates/links/" title="Category Title">Links</a></li>
</ul>
</li>
<li class="page_item games"><a href="#" title="Game Categories">Games</a>
<ul class="fade" style="z-index: 100; display: none; opacity: 0;">
	<li class="page_item category-1"><a href="#" title="Archives">Action</a></li>
	<li class="page_item category-2"><a href="#" title="Full Width">Adventure</a></li>
	<li class="page_item category-3"><a href="#" title="Links">Sports</a></li>
</ul>
</li>
<li class="page_item page-item-43"><a href="#" title="Theme Options">Advertise</a></li>
				<li>
				<form method="get" id="searchform" action="search.php">

				  <div id="search">
    <input type="hidden" name="cx" value="014838308468683311433:spe8e_fvvo4" />
    <input type="hidden" name="cof" value="FORID:10" />
    <input type="hidden" name="ie" value="UTF-8" />
	<input class="searchinput-google" value="Search this site... " onclick="this.value='';" name="q" id="s" type="text">
	<input class="searchsubmit" name="sa" value="Search" type="submit">
<script type="text/javascript" src="http://www.google.com/cse/brand?form=cse-search-box&lang=en"></script>

				  </div>
				</form>
				</li>
			</ul>
		</div> <!--end: menu-->
	</div> <!--end: right-->
	<div class="clear"></div>
</div> <!--headline-->



<div id="wrapper">
HTML;
