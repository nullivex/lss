<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['all'] = <<<HTML
	<h1>All Categories</h1>
	{categories}
HTML;

$templates['all_cat_row'] = <<<HTML
	<h2><a href="{url}">{name}</a></h2>
HTML;

$templates['games'] = <<<HTML
	<div class="categoryinfo">
		<div class="categorycol">
			<h2 class="widgettitle">Categories > Games in {cName}</h2>
			
			<table cellspacing='0' cellpadding='3' style="width:1%;">
			<tr>
			{games}
			</tr>
			</table>
		
			{pagination}
			
		</div>
	</div>
	<div class="categoryright">

		<div class="categoryrightcol">
			<img src="http://www.webpencil.com/banneroutlines/425x500.gif" width="425" />
		</div>

	</div>
HTML;

$templates['row_break'] = <<<HTML
	</tr>
	<tr>
HTML;

?>
