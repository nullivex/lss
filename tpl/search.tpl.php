<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['search'] = <<<HTML
<div id="fullcontent">
	<div class="right">{results} results for <span class="bold">{phrase}</span></div>
	<p class="browse">Search results for: <span class="bold">{phrase}</span></p>

	<div style="margin-top: 10px;">
		<table><tr>
			{games}
		</tr></table>
		<div class="right"><a href="{url_all}">View All Results</div>
		<div class="clear"></div>
	</div>

	<div style="margin-top:15px;">
	<div id="cse-search-results"></div>
	<script type="text/javascript">
	  var googleSearchIframeName = "cse-search-results";
	  var googleSearchFormName = "cse-search-box";
	  var googleSearchFrameWidth = 938;
	  var googleSearchDomain = "www.google.com";
	  var googleSearchPath = "/cse";
	</script>
	<script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>

	</div>
</div> 
HTML;

$tpl['search_full'] = <<<HTML
<div id="fullcontent">
	<div class="right">{results} results for <span class="bold">{phrase}</span></div>
	<p class="browse">Search results for: <span class="bold">{phrase}</span></p>

	<div style="margin-top: 10px;">
		<table><tr>
			{games}
		</tr></table>
		<div class="pagination">
			<form action="{url_game_list}" method="get">
			<input type="hidden" name="act" value="search" />
			<input type="hidden" name="do" value="all" />
			<input type="hidden" name="page_count" value="{page_count}" />
			<input type="hidden" name="q" value="{phrase}" />
			Pages: <input class="searchsubmit" name="prev" value="&lt;&lt; Previous" type="submit">
			{page_no} of {page_count}
			<input class="searchsubmit" name="next" value="Next &gt;&gt;" type="submit">
			<input type="text" name="page_no" value="{page_no}" size="3" />
			<input class="searchsubmit" name="goto" value="Go &gt;&gt;" type="submit">
			</forn>
		</div>
	</div>
	
</div>
HTML;

$tpl['game_row'] = <<<HTML
{row}
<td style="padding-right: 15px;">
	<a href="{url}">
		<img src="{thumb}" class="small_thumb" style="width: 130px; height: 110px;"><br />
		<b>{name}</b><br />
		Plays:  {plays}
	</div>
</td>
HTML;
