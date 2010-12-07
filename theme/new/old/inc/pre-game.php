<?php
/*
File: templates/default/inc/pre-game.php
This script is copyright to ArcadeTradeScript.com. Unauthorized use or distribution of this script is illegal.
*/

// wonderful hack to add category name to play links
$gameLink = str_ireplace("play/","play/".seo_str_plain($gameCatName)."/",$gameLink);
?>


<div class="gameinfo">

<div class="gameinfocol">

        <div class="gameinfo-menubar">
          &nbsp;&nbsp;<strong>Information -> <?=$contentTitle;?></strong> |
		<span>Plays: <?=$timesPlayed;?> <span> |
		<a href="#" class="note">Rate this game</a>
       </div>

<table>
<tr>
<td>
<img src="http://www.webpencil.com/banneroutlines/250x250.gif" />
</td>
<td valign="top" style="margin: 0 auto;">

    	<div class="gameinfoplay"><a href="<?=$gameLink;?>" class="gamelink">
		<span class="thumb"><img src="<?=$gameIcon1;?>" width="110" height="90" /></span>
		<span class="link">Click here to play</span><br />
<br />
		<span style="text-align:center;"><?=$gameName;?></span><br /><br />
		<span class="link">Click here to play</span><br /></a>
</div>



    	<div class="gameinfodesc">
		<span><strong>Instructions: </strong><?=$gameHow;?></span><br /><br />
		<span><strong>Description: </strong><?=$gameDesc;?> </span>


</div>



</td>
</tr>
</table>

        <div class="gameinfo-menubar" style="margin-top:10px;">
          &nbsp;&nbsp;<strong>Related Games</strong> |
		<a href="#" class="note">Share above game</a>|
		<a href="#" class="note">Report above game</a>|		
 </div>


<table>
<tr>


<td valign="top">
<div>
<table cellpadding="2">

<tr>
<? otherGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$gameCat,6); ?>
</tr>   
<tr>
<? otherGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$gameCat,6); ?>
</tr>                               

</table>

</div>
</td>
</tr>
</table>



</div>





</div>

<div class="right">

<div class="rightcol">
<table cellspacing="0" cellpadding="0">
<tr>

<td width="180" class="rightcolbox" style="margin-right:5px;" valign="top">
<h2>Top Players</h2>
<ol>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
<li>player name</li>
</ol>
<h2>Random Games</h2>
<ol>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>
<li>random gameplug</li>

</ol>
</td>

<td width="160" valign="top">
<img src="http://www.webpencil.com/banneroutlines/160x600.gif" />
</td></tr>
</table>
</div>




	</div> 
