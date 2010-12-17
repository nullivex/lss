<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['home'] = <<<HTML
<div id="leftwrapper">
{main_menu}
<div id="column2">
	<div id="slider">
		<div id="featured">
			{featured_games}
			<ul class="ui-tabs-nav">
				{featured_nav}
			</ul>
		</div>
	</div>
	<div class="postbox-large left">
		<h1><a href="#">Most Played Games</a></h1>
		<div class="boxcontent">
			<div class="thumb">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
				{most_played_games}
				</tr></tbody>
				</table>
		    </div>
		</div>
	</div>
	<div class="clear"></div>
    <div class="postbox left">
		<h1><a href="#">Recently Added Games</a></h1>
		<div class="boxcontent">
			<div class="thumb">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
				{recently_added_games}
				</tr></tbody>
				</table>
		    </div>
		</div>
	</div>
	<div class="postbox right">
		<h1><a href="#">Random Games</a></h1>
		<div class="boxcontent">
			<div class="thumb">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
				{random_games}
				</tr></tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div> <!--end: column2-->
</div> <!--end: leftwrapper-->
<div id="sidebar">
	{ad_home_tr_300x250}
	<div class="fullwidget">
		<h3>Recently Played Games</h3>
		<div class="boxcontent">
			<div class="thumb">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr>
				{recently_played_games}
				</tr></tbody>
				</table>
			</div>
		</div>
	</div> <!--end: fullwidget-->
	<div class="tabber">
		<ul id="tabs" class="tabs">
			<!--<li><a href="#popular-posts" rel="popular-posts" class="selected">Top Players</a></li>
			<li><a class="" href="#recent-comments" rel="recent-comments">Comments</a></li>-->
			<li><a class="" href="#tag-cloud" rel="tag-cloud">Tags</a></li>
		</ul>
		<div class="clear"></div>
		<!--<ul style="display: block;" id="popular-posts" class="tabcontent">
			<table width="98%" border="0" cellpadding="20" cellspacing="0" class="table-noborder">
			<tr>
				<td valign="middle" align="left" width="3%"></td>
				<td valign="bottom" align="left" width="65%">Username</td>
				<td valign="bottom" align="right" width="30%">Game Points</td>
			</tr>
			{top_players}
			</table>
		</ul>
		<ul style="display: none;" id="recent-comments" class="tabcontent">
			{comments}
		</ul>-->
		<ul style="display: none;" id="tag-cloud" class="tabcontent">
			{tags}
		</ul>
		<script type="text/javascript">
			var tabs=new ddtabcontent("tabs")
			tabs.setpersist(false)
			tabs.setselectedClassTarget("link")
			tabs.init()
		</script>
	</div>
	<div class="clear"></div>
  	<div class="leftwidget">
		<h3>Pages</h3>
		<div class="clear"></div>
		<div class="box">
			<ul>
				<li class="page_item page-item-2"><a href="http://www.theme-junkie.com/demo/portal/about/" title="About">About</a></li>
				<li class="page_item page-item-29"><a href="http://www.theme-junkie.com/demo/portal/elements/" title="Elements">Elements</a></li>
				<li class="page_item page-item-31">
					<a href="http://www.theme-junkie.com/demo/portal/page-templates/" title="Page Templates">Page Templates</a>
					<ul>
						<li class="page_item page-item-39"><a href="http://www.theme-junkie.com/demo/portal/page-templates/links/" title="Links">Links</a></li>
					</ul>
				</li>
				<li class="page_item page-item-43"><a href="http://www.theme-junkie.com/demo/portal/theme-options/" title="Theme Options">Theme Options</a></li>
			</ul>
		</div>
	</div>
  	<div class="rightwidget">
		<h3>Blogroll</h3>
		<div class="clear"></div>
		<div class="box">
			<ul class="xoxo blogroll">
				<li><a href="http://wordpress.org/development/">Development Blog</a></li>
				<li><a href="http://codex.wordpress.org/">Documentation</a></li>
				<li><a href="http://wordpress.org/extend/plugins/">Plugins</a></li>
				<li><a href="http://wordpress.org/extend/ideas/">Suggest Ideas</a></li>
				<li><a href="http://wordpress.org/support/">Support Forum</a></li>
			</ul>
		</div>
	</div>
</div> <!--end: sidebar-->
HTML;

$tpl['featured_row'] = <<<HTML
<div style="" id="post-{game_id}" class="ui-tabs-panel ui-tabs-hide">
	<a href="{url}" rel="bookmark"><img src="{large}" alt="{name}" width="373" height="236"></a>
	<div class="info">
		<h2><a href="{url}" rel="bookmark">{category} - {name}</a></h2>
	</div>
</div>
HTML;

$tpl['featured_nav_row'] = <<<HTML
<li class="ui-tabs-nav-item" id="nav-post-{game_id}">
	<a href="#post-{game_id}"><img src="{icon}" alt="{category} | {name}" height="44" width="80"></a>
</li>
HTML;

$tpl['most_played_row'] =  $tpl['recently_played_row'] = <<<HTML
{row}
<td style="height: 90px;" align="center" valign="middle">
	<a href="{url}">
		<span class="bold">{name}</span><br />
		<img src="{thumb}" alt="{name}" height="110" width="130">
	</a>
</td>
HTML;

$tpl['recently_added_row'] = $tpl['random_row'] = <<<HTML
{row}
<td style="height: 90px;" align="center" valign="middle">
	<a href="{url}">
		<span class="bold">{name}</span><br />
		<img src="{icon}" alt="{name}" height="75" width="90">
	</a>
</td>
HTML;

$tpl['top_player_row'] = <<<HTML
<tr style="color: #000000;">
	<td width="15" class="row1" align="center">{rank}</td>
	<td class="row1" align="left"><b><a href="#">{username}</a></b></td>
	<td class="row1" align="right">{points}</td>
</tr>
HTML;

$tpl['comment_row'] = <<<HTML
<li>
	<a href="{member_url}">{username}</a> on <a href="{game_url}">{game_name}</a>
</li>
HTML;

$tpl['tag_link'] = <<<HTML
<a href="{url}" class="tag-link-{tag_id}" title="{tag}" style="font-size: {size}pt;">{tag}</a> 
HTML;
