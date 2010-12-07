<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['pregame'] = <<<HTML
	<div class="gameinfo">
		<div class="gameinfocol">
			
			{admin}
		
			<div class="gameinfo-menubar">
				<strong>Information -> {name}</strong> |
				Plays: {played}
			</div>
		
			<table>
			<tr>
				<td><img src="http://www.webpencil.com/banneroutlines/250x250.gif" /></td>
				<td valign="top">
				
					<div class="gameinfoplay">
						<a href="{url}" class="gamelink">
						<span class="thumb"><img src="{icon}" width="110" height="90" alt="{name}" /></span>
						<span class="link">Click here to play</span><br /><br />
						<span style="text-align: center;">{name}</span><br /><br />
						<span class="link">Click here to play</span><br /><br />
						</a>
					</div>
				
					<div class="gameinfodesc">
						<span><strong>Instructions: </strong> {instructions}</span>
						<br /><br />
						<span><strong>Description: </strong> {description}</span>
					</div>
				</td>
			</tr>
			</table>
		
			<div style="clear: both;"></div>
		
			<div class="gameinfo-menubar" style="margin-top:10px;">
				<strong>Related Games</strong>
			</div>
			<div>
				{related_games}
			</div>
		
		</div>
	</div>
	
	<div class="right">
		<div class="rightcol">
			<table cellspacing="0" cellpadding="0">
			<tr>
				<td width="180" class="rightcolbox" style="margin-right:5px;" valign="top">

					<h2>Random Games</h2>
					{random_games}
					
				</td>
				<td width="160" valign="top">
					<img src="http://www.webpencil.com/banneroutlines/160x600.gif" />
				</td>
			</tr>
			</table>
		</div>
	</div>
HTML;

?>
