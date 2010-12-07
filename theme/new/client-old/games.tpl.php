<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['admin_game'] = <<<HTML
	<div class="gameAdmin">
		{message}
		<h3>Manage {gName}</h3>
		<ul>
			<li>- <a href="{site_url}/admin/index.php?a=edit_game&id={gid}" rel="shadowbox;width=900;height=600;">Edit Game</a></li>
			<li>- <a href="{feature_url}">{feature_action}</a></li>
			<li>- <a href="{trade_url}">{trade_action}</a></li>
			<li>- <a href="{active_url}">{active_action}</a></li>
		</ul>
	</div>
HTML;

$templates['admin_message'] = <<<HTML
		<div class="gameAdminMessage">
			{message}
		</div>
HTML;

$templates['game_row'] = <<<HTML
	<td style="width:25%; padding-right:9px; padding-left:9px;">
		<a href="{url}">
			<img src="{icon}" width="110" height="90" class="small_thumb" alt="{name}" /><br />
			<strong>{name}</strong>
		</a>
		<br />
		<div style="line-height:15px;">
			<div>Plays: {plays}</div>
		</div>
	</td>
HTML;

$templates['game_small_row'] = <<<HTML
	<td align="center" valign="middle" style="height: 90px;">
		<a href="{url}">
			{name}
			<img src="{icon}" width="90" height="75" alt="{name}" />
		</a>
	</td>
HTML;

$templates['game_side_row'] = <<<HTML
	<td align="center" valign="middle" style="height: 90px; padding: 10px;">
		<a href="{url}">
			<img src="{icon}" width="110" height="90" alt="{name}" />
		</a>
	</td>
HTML;

$templates['game_mini_row'] = <<<HTML
	<td align="center" valign="middle" style="height: 90px;">
		<a href="{url}">
			<img src="{icon}" width="90" height="75" alt="{name}" />
		</a>
	</td>
HTML;

$templates['game_block'] = <<<HTML
	<table cellpadding="0" cellspacing="0" width="100%">
	{games}
	</table>
HTML;

$templates['game_full_row'] = <<<HTML
	<tr>
		<td valign="top" style="padding: 2px;">
			<a href="{url}">
				<img src="{icon}" width="110" height="90" class="small_thumb" alt="{name}" />
			</a>
		</td>
		<td valign="top" style="padding: 2px;">
			<div><strong><a href="{url}">{name}</a></strong></div>
			<div>{desc}</div>
		</td>
	</tr>
HTML;

$templates['row_break'] = <<<HTML
	</tr>
	<tr>
HTML;

?>
