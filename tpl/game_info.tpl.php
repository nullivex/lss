<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['game_info'] = <<<HTML
<div id="pagewrapper">
	{main_menu}
	<div id="content" style="margin-bottom:10px;">
		<p class="browse">You Are Here: <a href="{url_home}">Home</a> > <a href="{url_category}">{category}</a> > <a href="{url_game}">{name}</a></p>
		<div class="archive" align="center">
			<table><tbody><tr>
				<td>{ad_game_info_300x250}<br /><img src="include/images/advertisement-footer.png"></td>
				<td valign="top">
					<div class="gameinfoplay">
						<a href="{url_game_play}" class="gamelink">
						<span class="thumb"><img src="{thumb}" alt="Space trophy" height="100" width="120"></span>
						<span class="link">Click here to play</span><br /><br />
						<span style="text-align: center;">{name}</span><br /><br /><br />
						<span class="link">Click here to play</span><br /><br /
						</a>
					</div>
					<div class="gameinfodesc">
						<span class="instructions-icon">Instructions: </span> {inst}
						<br /><br />
						<span class="description-icon">Description: </span> {desc}
					</div>
				</td>
			</tr></tbody></table>
		</div>
	</div>
	<div id="content-gameinfo-noborder">
		<div class="tabber-gameinfo">
			<ul id="tabs" class="tabs-gameinfo">
				<li><a href="#popular-posts" rel="popular-posts" class="selected">Related Games</a></li>
				<li><a class="" href="#recent-comments" rel="recent-comments">Featured Games</a></li>
				<li><a class="" href="#monthly-archives" rel="monthly-archives">Tags</a></li>
				<li><a class="" href="#tag-cloud" rel="tag-cloud">Comments</a></li>
			</ul>
			<div class="clear"></div>
				<ul style="display: block;" id="popular-posts" class="tabcontent-gameinfo">
				<table style="width: 1%;" cellpadding="3" cellspacing="0">
				<tbody><tr>
				{related_games}
				</tr></tbody></table>
			</ul>
			<ul style="display: none;" id="recent-comments" class="tabcontent-gameinfo">
				<table style="width: 1%;" cellpadding="3" cellspacing="0">
				<tbody><tr>
				{featured_games}
				</tr></tbody></table>
			</ul>
			<ul style="display: none;" id="monthly-archives" class="tabcontent-gameinfo">
				{tags}
			</ul>
			<ul style="display: none;" id="tag-cloud" class="tabcontent-gameinfo">
				Coming Soon!
				{comments}
			</ul>
			<script type="text/javascript">
				var tabs=new ddtabcontent("tabs")
				tabs.setpersist(false)
				tabs.setselectedClassTarget("link")
				tabs.init()
			</script>
		</div> <!--end: tabber-->
	</div>
   	<div class="clear"></div>
  	<div class="pagenavi">
		<div class="nextprev left"></div>
    	<div class="nextprev right"></div>
		<div class="clear"></div>
	</div> <!--end: pagenavi-->
</div> <!--end: pagewrapper-->

<div id="pageright">
	{ad_game_info_160x600}
</div>

<div class="clear"></div>
</div> <!--end: wrapper-->
HTML;

$tpl['related_row'] =  $tpl['featured_row'] = <<<HTML
{row}
<td style="width: 25%; padding-right: 12px; padding-left: 12px;">
	<a href="{url}">
		<img src="{thumb}" class="small_thumb" alt="Airwar" height="100" width="120"><br>
		<strong>{name}</strong>
	</a>
	<br>
	<div><div><small>Plays: {plays}</small></div></div>
</td>
HTML;

$tpl['tag_link'] = <<<HTML
<a href="{url}" class="tag-link-{tag_id}" title="{tag}" style="font-size: {size}pt;">{tag}</a>
HTML;
