<?php
/*
File: templates/default/inc/play-game.php
This script is copyright to ArcadeTradeScript.com. Unauthorized use or distribution of this script is illegal.
*/
?>


<div class="gameplay">

<div class="gameplaycol">

      <div class="holder-top"><!-- empty --></div>
      <div class="holder-bkg2">

<strong>Related Games</strong>

      
		
		<table width="760" border="0" cellspacing="0">
          <tbody><tr>
            <td valign="top" align="center"><!-- InstanceBeginEditable name="Content" -->


</td>
          </tr>
        </tbody></table> 
	
<table align="center">
<tr>


<td valign="top">
<div>
<table cellpadding="1">

<tr>
	<? otherGames($baseDir,$templateBase,$seoFriendly,$preGamePageStatus,$gameCat,7); ?>
</tr>                                

</table>

</div>
</td>
</tr>
</table>




      </div>






</div>



<div class="gameplaycol">

      <div class="holder-top-line"><!-- empty --></div>
      <div class="holder-bkg">
        <div class="holder-menu">
		  <div class="holder-forum"><a href="#" class="note">Game Forum</a>&nbsp;&nbsp;</div>
          &nbsp;&nbsp;<strong><?=$contentTitle;?></strong> |
		<a href="#" class="note">Instructions</a>|
		<a href="#" class="note">Description</a>|
		<a href="#" class="note">Rate Game</a>|		
<a href="#" class="note">Zoom</a>        </div>

      
		
		<table width="760" border="0" cellspacing="0">
          <tbody><tr>
            <td valign="top" align="center"><!-- InstanceBeginEditable name="Content" -->

<? showGame($templateBase,$fileType,$gameFile,$gameWidth,$gameHeight,$customCode); ?>
</td>
          </tr>
        </tbody></table> 
	
		<div class="holder-menur">
		  <div class="holder-forum"><a href="#" class="note">Game Forum</a>&nbsp;&nbsp;</div>
 		&nbsp;&nbsp;<strong>Related Games</strong> |
		<a href="http://www.clubpenguin.com/parents/" class="note">Parents Guide </a>|
		<a href="http://www.clubpenguin.com/terms.htm" class="note">Terms</a>|
		<a href="http://www.clubpenguin.com/privacy.htm" class="note">Privacy</a>&nbsp;&nbsp;</div>



      </div>





      <div class="holder-bottom"><!-- empty --></div>
</div>
	  





</div>

<div class="gameinforight">

<div class="gameinforightcol">
<img src="http://www.webpencil.com/banneroutlines/160x600.gif" />
</div>




	</div> 


                           