<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates['playgame'] = <<<HTML
	<div class="gameinforight">
		<div class="gameinforightcol">
			<img src="http://www.webpencil.com/banneroutlines/160x600.gif" />
		</div>
	</div>
	<div class="gameplay">
		<div class="gameplaycol">
			
			{admin}
			
			<div class="holder-top"><!-- No Content --></div>
			<div class="holder-bkg2">
				<strong>Related Games</strong>
				
				<table width="760" border="0" cellspacing="0">
				<tbody>
				<tr>
					<td valign="top" align="center">
						<!-- InstanceBeginEditable name="Content" -->
					</td>
				</tr>
				</tbody>
				</table>
				
				{related_games}
				
			</div>
			
		</div>
		<div class="gameplaycol">
		
			<div class="holder-top-line"><!-- No Content --></div>
			<div class="holder-bkg">
			
				<div class="holder-menu">
					<div class="holder-forum"><a href="#" class="note"><!--Game Forum--></a>&nbsp;&nbsp;</div>
					&nbsp;&nbsp;<strong>Playing -> {name}</strong> |
					<a href="#" class="note">Instructions</a>|
					<a href="#" class="note">Description</a>|
					<a href="#" class="note">Rate Game</a> |
					<a href="#" class="note">Zoom</a>
				</div>
				
				<table width="760" border="0" cellspacing="0">
				<tbody>
				<tr>
					<td valign="top" align="center">
						{embed_code}
					</td>
				</tr>
				</tbody>
				</table>
				
				<div class="holder-menur">
					<div class="holder-forum"><a href="#" class="note"><!--Game Forum--></a>&nbsp;&nbsp;</div>
					&nbsp;&nbsp;<strong>Related Games</strong> |
					<a href="http://www.clubpenguin.com/parents/" class="note">Parents Guide </a>|
					<a href="http://www.clubpenguin.com/terms.htm" class="note">Terms</a>|
					<a href="http://www.clubpenguin.com/privacy.htm" class="note">Privacy</a>&nbsp;&nbsp;</div>
				</div>
				
			</div>
		</div>
		<div style="clear: both;"></div>
		<div class="holder-bottom"><!-- empty --></div>
	</div>
HTML;

$templates['embed'] = <<<HTML
		<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" id="game"  height="421.686746988" width="700">
			<param name="movie" value="{file}" />
			<param name="quality" value="high" />

			<param name="allowscriptaccess" value="never" />
			<embed src="{file}" height="{height}" width="{width}" quality="high" name="FlashContent" allowscriptaccess="never" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>
		</object>
HTML;

?>
