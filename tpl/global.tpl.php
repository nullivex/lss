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


				<li class="haschild hide"><a href="http://beta.sloppygames.com/topnews.php" title="View all posts filed under Top News">Top News</a>
<ul style="overflow: visible; z-index: 100; display: none; height: 93px;" class="blindv">
	<li class="cat-item cat-item-53haschild"><a href="http://beta.sloppygames.com/page.php" title="View all posts filed under Category #1">Post</a>
	<ul style="z-index: 100; display: none;" class="blindv">
	<li class="cat-item cat-item-58"><a href="http://beta.sloppygames.com/page.php" title="View all posts filed under Category #1 #1">Category #1 #1</a>
</li>
	<li class="cat-item cat-item-59"><a href="#" title="View all posts filed under Category #1 #2">Category #1 #2</a>
</li>
	<li class="cat-item cat-item-60"><a href="#" title="View all posts filed under Category #1 #3">Category #1 #3</a>
</li>
	</ul>
</li>
	<li class="cat-item cat-item-54"><a href="{url_game_list}" title="">Category Games</a>
</li>
	<li class="cat-item cat-item-55"><a href="http://beta.sloppygames.com/register.php" title="View all posts filed under Category #3">Register</a>
</li>
</ul>
</li>

	<li class="haschild hide"><a href="#" title="View all posts filed under Sports">Play Game</a>
<ul style="overflow: visible; z-index: 100; display: none; height: 121px;" class="blindv">
	<li class="cat-item cat-item-61"><a href="{url_game_info}" title="">Game Info</a>
</li>
	<li class="cat-item cat-item-62"><a href="{url_game_play}" title="">Game Play</a>
</li>
	<li class="cat-item cat-item-63"><a href="{url_traffic_stats}" title="">Traffic Stats</a>
</li>
	<li class="cat-item cat-item-64"><a href="http://beta.sloppygames.com/member-stats.php" title="">Member Stats</a>
</li>
</ul>
</li>
<li class="cat-item cat-item-6"><a href="#" title="View all posts filed under Technology">Technology</a>
</li>

		</ul>
	</div> <!--end: menu-->



	<div class="leftsidebar">
			<h3><a href="#">Recent News</a></h3>
		<div class="box">
						<div class="leftnews">
				<div class="thumb">
					<a href="#" rel="bookmark"><img src="http://beta.sloppygames.com/include/timthumb_006.png" alt="ITV appoints Royal Mail boss Crozier CEO"></a>
				</div> <!--end: thumb-->
				<span><a href="#" rel="bookmark">ITV appoints Royal Mail boss Crozier CEO</a></span>
				<div class="clear"></div>
			</div> <!--leftnews-->

						<div class="leftnews">
				<div class="thumb">
					<a href="#" rel="bookmark"><img src="http://beta.sloppygames.com/include/timthumb_004.png" alt="The Apple iPad: It’s just ahead of its time ‎"></a>
				</div> <!--end: thumb-->
				<span><a href="#" rel="bookmark">The Apple iPad: It’s just ahead of its time ‎</a></span>
				<div class="clear"></div>
			</div> <!--leftnews-->

					</div>

					<h3>Partner Sites</h3><div class="clear"></div><div class="box">		<ul>
<li><a href="#">Addicting Games</a></li>
<li>Free Flash Games</li>
<li>Mario Games</li>
<li>Fun Games Online</li>
<li>Play Free Arcade</li>
				</ul>
		</div>

	<div class="ad160x600">
	<a href="http://www.theme-junkie.com/" target="_blank"><img src="http://beta.sloppygames.com/include/160x600.jpg"></a></div>

<!--<img src="http://www.webpencil.com/banneroutlines/160x600.gif" />-->	</div> <!--end: leftsidebar-->


</div> <!--end: column1-->
HTML;
