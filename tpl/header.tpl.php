<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['header'] = <<<HTML
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head profile="http://gmpg.org/xfn/11">

<title>{site_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="cache-control" content="no-cache" />
<meta name="keywords" content="free games, free online games, games, online games, play games, racing games, car games, sports games, action games, adventure games, arcade games, shooting games, addicting games" />
<meta name="description" content="Play over 15000 FREE games here at SloppyGames.com, including free online games, arcade games, racing games, shooting games, and flash games!" />
<link rel="stylesheet" type="text/css" href="{css}/game.css" media="screen" />
<link rel="stylesheet" type="text/css" href="{css}/style.css" media="screen" />
<script language="javascript" type="text/javascript" src="{js}/ajaxtabs.js"></script>
<script type="text/javascript" src="{js}/menu.js"></script>
<script type="text/javascript" src="{js}/jquery.js"></script>
<script type="text/javascript" src="{js}/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#featured > ul").tabs({fx:{opacity: "toggle"}}).tabs("rotate", 3500, true);
	});
</script>

<!--[if lt IE 7]>
<script type="text/javascript" src="http://www.theme-junkie.com/demo/portal/wp-content/themes/portal/javascripts/unitpngfix.js"></script>
<link rel="stylesheet" type="text/css" href="http://www.theme-junkie.com/demo/portal/wp-content/themes/portal/ie.css" />
<![endif]-->

</head>
<body>

<div id="header">
	<div class="left"><a class="imagelogo" href="{url_home}"></a></div>
	
	<div class="right">
	
	{ad_home_right_468x60}
	
	</div>
</div>
<div id="headline">
	<div class="left">
		<span class="home-icon"><a href="{url_home}" title="Home">Home</a></span>
		<span class="login-icon"><a href="{url_login}" title="Login">Login</a></span>
		<span class="register-icon"><a href="{url_register}" title="Register">Register</a></span>
	</div>
	<div class="right">
		<div class="mlmenu horizontal fade inaccesible">
			<ul>
				<li class="page_item page-item-2"><a href="{url_page}about" title="About">About</a></li>
				<li class="page_item games"><a href="{url_game_list}" title="Game Categories">Games</a>
				<ul class="fade" style="z-index: 100; display: none; opacity: 0;">
					<li class="page_item category-1"><a href="#" title="Archives">Action</a></li>
					<li class="page_item category-2"><a href="#" title="Full Width">Adventure</a></li>
					<li class="page_item category-3"><a href="#" title="Links">Sports</a></li>
				</ul>
				</li>
				<li class="page_item page-item-43"><a href="{url_page}advertise" title="Theme Options">Advertise</a></li>
				<li>
				<form method="get" id="searchform" action="index.php">
				<input type="hidden" name="act" value="search" />
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
{alert}
HTML;
