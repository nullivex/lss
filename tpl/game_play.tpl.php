<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['game_play'] = <<<HTML
<div id="pagewrapper">
	<div id="content-gameplay" style="margin-bottom:10px;">
		<p class="browse">Playing -&gt; <a href="{url_category}">{category}</a> -&gt; <a href="{url_game}">{name}</a></p>
		<div class="archive" align="center">
		<p style="1px solid #cccccc;"> <a href="#">instructions</a> | <a href="#">description</a> | <a href="#">rate</a> | <a href="#">-</a> zoom <a href="#">+</a> </p> <br />
			<embed src="{media}" menu="false" quality="high" width="{width}" height="{height}" type="{media_type}" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		</div>
	</div>
	<div id="content-gameplay-noborder">
		<div class="tabber-gameplay">
			<ul id="tabs" class="tabs-gameplay">
				<li><a href="#popular-posts" rel="popular-posts" class="selected">Related Games</a></li>
				<li><a class="" href="#recent-comments" rel="recent-comments">Featured Games</a></li>
				<li><a class="" href="#monthly-archives" rel="monthly-archives">Tags</a></li>
				<li><a class="" href="#tag-cloud" rel="tag-cloud">Comments</a></li>
			</ul>
			<div class="clear"></div>
			<ul style="display: block;" id="popular-posts" class="tabcontent-gameplay">
				<div class="left">
					{ad_play_related_300x250}
				</div>
				<table style="width: 60%; padding-right:20px;" cellpadding="3" cellspacing="0" class="right">
				<tbody><tr>
				{related_games}
				</tr></tbody>
				</table>
				<div class="clear"></div>
			</ul>
			<ul style="display: none;" id="recent-comments" class="tabcontent-gameplay">
				<table style="width: 1%;" cellpadding="3" cellspacing="0">
				<tbody><tr>
				{featured_games}
				</tr></tbody>
				</table>
				<div class="clear"></div>
			</ul>
			<ul style="display: none;" id="monthly-archives" class="tabcontent-gameplay">
				{tags}
				<div class="clear"></div>
			</ul>
			<ul style="display: none;" id="tag-cloud" class="tabcontent-gameplay">
				Coming Soon
				<div class="clear"></div>
			</ul>

			<script type="text/javascript">
				var tabs=new ddtabcontent("tabs")
				tabs.setpersist(false)
				tabs.setselectedClassTarget("link")
				tabs.init()
			</script>
		</div> <!--end: tabber-->
    	<div class="clear"></div>
		<div class="pagenavi">
			<div class="nextprev left"></div>
			<div class="nextprev right"></div>
			<div class="clear"></div>
		</div> <!--end: pagenavi-->
	</div> <!--end: content-->
</div> <!--end: pagewrapper-->
<div id="pageright">
	{ad_play_right_160x600}
</div> <!--pageright-->	
<div class="clear"></div>
</div> <!--end: wrapper-->
HTML;

$tpl['game_play_full'] = <<<HTML
<div id="pagewrapper" style="width: 100%;">
	<div id="content-gameplay" style="margin-bottom:10px; width: 935px;">
		<p class="browse">Playing -&gt; <a href="{url_category}">{category}</a> -&gt; <a href="{url_game}">SmileyTrap</a></p>
		<div class="archive" align="center">
		<p style="1px solid #cccccc;"> <a href="#">instructions</a> | <a href="#">description</a> | <a href="#">rate</a> | <a href="#">-</a> zoom <a href="#">+</a> | <a href="#">full screen</a> </p> <br />
			<embed src="{media}" menu="false" quality="high" width="{width}" height="{height}" type="{media_type}" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		</div>
	</div>
	<div id="content-gameplay-noborder" style="width: 958px;">
		<div class="tabber-gameplay">
			<ul id="tabs" class="tabs-gameplay" style="width: 935px;">
				<li><a href="#popular-posts" rel="popular-posts" class="selected">Related Games</a></li>
				<li><a class="" href="#recent-comments" rel="recent-comments">Featured Games</a></li>
				<li><a class="" href="#monthly-archives" rel="monthly-archives">Tags</a></li>
				<li><a class="" href="#tag-cloud" rel="tag-cloud">Comments</a></li>
			</ul>
			<div class="clear"></div>
			<ul style="display: block; width: 935px;" id="popular-posts" class="tabcontent-gameplay">
				<div class="left">
					{ad_play_related_300x250}
				</div>
				<table style="width: 60%; padding-right:20px;" cellpadding="3" cellspacing="0" class="right">
				<tbody><tr>
				{related_games}
				</tr></tbody>
				</table>
				<div class="clear"></div>
			</ul>
			<ul style="display: none; width: 935px;" id="recent-comments" class="tabcontent-gameplay">
				<table style="width: 1%;" cellpadding="3" cellspacing="0">
				<tbody><tr>
				{featured_games}
				</tr></tbody>
				</table>
				<div class="clear"></div>
			</ul>
			<ul style="display: none; width: 935px;" id="monthly-archives" class="tabcontent-gameplay">
				{tags}
				<div class="clear"></div>
			</ul>
			<ul style="display: none; width: 935px;" id="tag-cloud" class="tabcontent-gameplay">
				Coming Soon
				<div class="clear"></div>
			</ul>

			<script type="text/javascript">
				var tabs=new ddtabcontent("tabs")
				tabs.setpersist(false)
				tabs.setselectedClassTarget("link")
				tabs.init()
			</script>
		</div> <!--end: tabber-->
    	<div class="clear"></div>
		<div class="pagenavi">
			<div class="nextprev left"></div>
			<div class="nextprev right"></div>
			<div class="clear"></div>
		</div> <!--end: pagenavi-->
	</div> <!--end: content-->
</div> <!--end: pagewrapper-->
<div class="clear"></div>
</div> <!--end: wrapper-->
HTML;

$tpl['featured_row'] = $tpl['related_row'] = <<<HTML
{row}
<td style="width: 25%; padding: 5px 10px 10px 10px;">
	<a href="{url}">
		<img src="{thumb}" class="small_thumb" alt="{name}" height="100" width="120"><br>
		<strong>{name}</strong><br />
		<small>Plays: {plays}</small>
	</a>
</td>
HTML;
