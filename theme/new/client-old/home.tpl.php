<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['home'] = <<<HTML
<div class="left">

	<div class="leftcol">
		<h2 class="widgettitle">Recent Added Games</h2>
		{recent_games}<br />
<div style="text-align: right; font-size: 14px;"><a href="#">view all latest games</a></div>
	</div>
</div>

<div class="right">
	<div class="rightcol">
		<img src="http://www.webpencil.com/banneroutlines/336x280.gif" />
	</div>
</div>

<div class="middle">

	<div class="post" id="middle">
		<h2>Most Played Games</h2>
		{most_games}
	</div>

	<div class="post">
		<h2>Featured Games</h2>
		{featured_games}
	</div>

</div>
HTML;

?>
