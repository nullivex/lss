<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['view'] = <<<HTML
<div id="fullcontent">
	<h2 class="pagetitle">{name}</h2>
	<div class="entry">
		{content}
	</div>
	<div class="clear"></div>
</div>
HTML;
