<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['game_list'] = <<<HTML
<div id="pagewrapper">
{main_menu}
	<div id="content">
		<div class="right">
			<form method="get" id="searchform" action="{url_game_list}">
			<input type="hidden" name="act" value="games" />
			<input type="hidden" name="do" value="list" />
			<input type="hidden" name="page_no" value="{page_no}" />
			<input type="hidden" name="category_id" value="{category_id}" />
			<input type="hidden" name="page_count" value="{page_count}" />
			<div id="search">
				Sort by :
				<select name="sort">
					<option value="name:asc" {sort_name}>Name</option>
					<option value="plays:desc" {sort_plays}>Most Played</option>
					<option value="created:desc" {sort_created}>Recently Added</option>
					<option value="last_played:desc" {sort_last_played}>Recently Played</option>
				</select>
				<input class="searchsubmit" value="GO" type="submit">
			</div>
			</form></div>

		<p class="browse">You Are Here: <a href="{url_game_list}">Games</a> -&gt; <a href="{url_category}">{category} Games</a></p>
		<div class="archive" align="center">
			<table style="width: 1%;" cellpadding="3" cellspacing="0">
			<tbody><tr>
			{games}
			</tr></tbody>
			</table>
			<div class="pagination">
				<form action="{url_game_list}" method="get">
				<input type="hidden" name="act" value="games" />
				<input type="hidden" name="do" value="list" />
				<input type="hidden" name="category_id" value="{category_id}" />
				<input type="hidden" name="page_count" value="{page_count}" />
				Pages: <input class="searchsubmit" name="prev" value="&lt;&lt; Previous" type="submit">
				{page_no} of {page_count}
				<input class="searchsubmit" name="next" value="Next &gt;&gt;" type="submit">
				<input type="text" name="page_no" value="{page_no}" size="3" />
				<input class="searchsubmit" name="goto" value="Go &gt;&gt;" type="submit">
				</forn>
			</div>
		</div>
    	<div class="clear"></div>
	</div> <!--end: content-->
</div>
<div id="pageright">
	{ad_list_160x600}
	<div class="clear"></div>
</div>
HTML;

$tpl['game_row'] = <<<HTML
{row}
<td style="width: 25%; padding-right: 12px; padding-left: 12px;">
	<a href="{url}">
		<img src="{thumb}" class="small_thumb" alt="{name}" height="100" width="120"><br />
		<strong>{name}</strong><br />
		<small>Plays: {plays}</small>
	</a>
</td>
HTML;

$tpl['category_list'] = <<<HTML
<div id="pagewrapper">
{main_menu}
	<div id="content">
		<p class="browse">You Are Here: <a href="{url_game_list}">Games</a> -&gt; Categories</a></p>
		<div class="archive" align="center">
			<table style="width: 100%;" cellpadding="3" cellspacing="0">
			<tbody><tr>
			{categories}
			</tr></tbody>
			</table>
		</div>
    	<div class="clear"></div>
	</div> <!--end: content-->
</div>
<div id="pageright">
	{ad_list_160x600}
	<div class="clear"></div>
</div>
HTML;

$tpl['category_row'] = <<<HTML
{row}
<td>
	<h2 style="border: 1px solid black; margin: 5px; padding: 5px;">
	<a href="{url}">
		<img src="{thumb}" alt="{name}" height="100" width="120" /><br />
		{name}<br />
		<small style="font-size: 11px;">Total Games: {games}</small>
	</a>
	</h2>
</td>
HTML;
