<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['login'] = <<<HTML

<div id="leftwrapper">

{main_menu}

	<div id="column2">
			<div id="content-smallpage">
<p class="browse">You are here: Login</p>
			

 
  <div class="pagenavi">

    <div class="clear"></div>

  </div>
      <div id="respond">

        <form action="#" method="post" id="commentform">
            <p>
        <input class="author" value="Enter your username" onclick="this.value='';" name="author" id="author" size="22" tabindex="1" type="text">
        <label for="author"></label>
      </p>
      <p>

        <input class="email" value="Enter your password" onclick="this.value='';" name="email" id="email" size="22" tabindex="2" type="text">
        <label for="email"></label>       <input class="submit" name="submit" id="submit" tabindex="5" value="Login" type="submit">

      </p>

           <p><a href="#"><small>&nbsp;Forgot your password? Forgot your username?</small></a></p>


 
          </form>
      </div>

  </div>



				
		<div class="clear"></div>
		
			
		<!--<div class="clear"></div>-->
		
				
			
	</div> <!--end: column2-->
</div> <!--end: leftwrapper-->
<div id="sidebar">
	<div class="ad300x250">

	<a href="#" target="_blank"><img src="http://beta.sloppygames.com/include/300x250.jpg"></a></div> <!--end: ad300x250-->
	<div class="tabber">
	<ul id="tabs" class="tabs">
		<li><a href="#popular-posts" rel="popular-posts" class="selected">Popular</a></li>
		<li><a class="" href="#recent-comments" rel="recent-comments">Comments</a></li>
		<li><a class="" href="#monthly-archives" rel="monthly-archives">Archives</a></li>
		<li><a class="" href="#tag-cloud" rel="tag-cloud">Tags</a></li>

	</ul>
<div class="clear"></div>

  	</div> <!--end: rightwidget-->

</div> <!--end: sidebar-->


<div class="clear"></div>
</div> <!--end: wrapper-->




HTML;
