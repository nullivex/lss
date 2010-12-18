<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['register'] = <<<HTML

<div id="leftwrapper">

{main_menu}

	<div id="column2">
			<div id="content-smallpage">
<p class="browse">You Are Here: Register</p>
   
 
  <div class="pagenavi">

    <div class="clear"></div>

  </div>
      <div id="respond">
<form action="#" method="post" id="commentform">
            <p>
        <input class="author" value="Enter your name..." onclick="this.value='';" name="author" id="author" size="22" tabindex="1" type="text">
        <label for="author"><small>
          (Required)          </small></label>
      </p>
      <p>

        <input class="email" value="Enter your email..." onclick="this.value='';" name="email" id="email" size="22" tabindex="2" type="text">
        <label for="email"><small>(Will not be published)
           (Required)          </small></label>
      </p>
      <p>
        <input class="url" value="Enter your website..." onclick="this.value='';" name="url" id="url" size="22" tabindex="3" type="text">
        <label for="url"><small>(Optional)</small></label>
      </p>
            <!--<p><small><strong>XHTML:</strong> You can use these tags: <code>&lt;a href=&quot;&quot; title=&quot;&quot;&gt; &lt;abbr title=&quot;&quot;&gt; &lt;acronym title=&quot;&quot;&gt; &lt;b&gt; &lt;blockquote cite=&quot;&quot;&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=&quot;&quot;&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=&quot;&quot;&gt; &lt;strike&gt; &lt;strong&gt; </code></small></p>-->

      <p>
        <textarea name="comment" id="comment" tabindex="4"></textarea>
      </p>
      <p>
        <input class="submit" name="submit" id="submit" tabindex="5" value="Submit" type="submit">
        <input name="comment_post_ID" value="99" id="comment_post_ID" type="hidden">
<input name="comment_parent" id="comment_parent" value="0" type="hidden">
      </p>
          </form>

      </div>

  </div>



				
		<div class="clear"></div>
		
			
		<!--<div class="clear"></div>-->
		
				
			
	</div> <!--end: column2-->
</div> <!--end: leftwrapper-->
<div id="sidebar">
	<div class="ad300x250">

	<a href="http://www.theme-junkie.com/" target="_blank"><img src="http://beta.sloppygames.com/include/300x250.jpg"></a></div> <!--end: ad300x250-->
	<div class="tabber">
	<ul id="tabs" class="tabs">
		<li><a href="#popular-posts" rel="popular-posts" class="selected">Popular</a></li>
		<li><a class="" href="#recent-comments" rel="recent-comments">Comments</a></li>
		<li><a class="" href="#monthly-archives" rel="monthly-archives">Archives</a></li>
		<li><a class="" href="#tag-cloud" rel="tag-cloud">Tags</a></li>

	</ul>
<div class="clear"></div>
	<ul style="display: block;" id="popular-posts" class="tabcontent">
		<li><a href="http://www.theme-junkie.com/demo/portal/2009/12/28/bill-clinton-urges-business-leaders-to-help-haiti/" title="Bill Clinton urges business leaders to help Haiti">Bill Clinton urges business leaders to help Haiti</a></li>
<li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/kristen-bells-rising-career-takes-her-to-rome/" title="Kristen Bell's rising career takes her to " rome="">Kristen Bell's rising career takes her to "Rome"</a></li>
<li><a href="http://www.theme-junkie.com/demo/portal/2008/12/28/hello-world/" title="Hello world!">Hello world!</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/the-apple-ipad-its-just-ahead-of-its-time-%e2%80%8e/" title="The Apple iPad: It's just ahead of its time ?">The Apple iPad: It's just ahead of its time ?</a></li>
<li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/obama-vows-to-fight-on-after-tough-year/" title="Obama vows to fight on after tough year">Obama vows to fight on after tough year</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/darling-says-breaking-up-banks-not-the-answer/" title="Darling says breaking up banks not the answer">Darling says breaking up banks not the answer</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/itv-appoints-royal-mail-boss-crozier-ceo/" title="ITV appoints Royal Mail boss Crozier CEO">ITV appoints Royal Mail boss Crozier CEO</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2009/11/28/wall-st-hits-back-at-obamas-bank-curbs/" title="Wall St hits back at Obama's bank curbs">Wall St hits back at Obama's bank curbs</a></li>	</ul>

	<ul style="display: none;" id="recent-comments" class="tabcontent">
		<li><a href="http://www.theme-junkie.com/demo/portal/2008/12/28/hello-world/#comment-66">desmond</a> on <a href="http://www.theme-junkie.com/demo/portal/2008/12/28/hello-world/">Hello world!</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/publishers-embrace-ipad-but-revolution-unlikely/#comment-33">asif eminov</a> on <a href="http://www.theme-junkie.com/demo/portal/2010/01/28/publishers-embrace-ipad-but-revolution-unlikely/">Publishers embrace iPad, but revolution unlikely</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/fiorentina-striker-mutu-fails-drugs-test/#comment-32">mike</a> on <a href="http://www.theme-junkie.com/demo/portal/2010/01/28/fiorentina-striker-mutu-fails-drugs-test/">Fiorentina striker Mutu fails drugs test</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/cameron-says-deficit-cuts-must-start-in-2010/#comment-29">admin</a> on <a href="http://www.theme-junkie.com/demo/portal/2010/01/28/cameron-says-deficit-cuts-must-start-in-2010/">Cameron says deficit cuts must start in 2010</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/cameron-says-deficit-cuts-must-start-in-2010/#comment-28">admin</a> on <a href="http://www.theme-junkie.com/demo/portal/2010/01/28/cameron-says-deficit-cuts-must-start-in-2010/">Cameron says deficit cuts must start in 2010</a></li><li><a href="http://www.theme-junkie.com/demo/portal/2010/01/28/cameron-says-deficit-cuts-must-start-in-2010/#comment-27">admin</a> on <a href="http://www.theme-junkie.com/demo/portal/2010/01/28/cameron-says-deficit-cuts-must-start-in-2010/">Cameron says deficit cuts must start in 2010</a></li>	</ul>

	<ul style="display: none;" id="monthly-archives" class="tabcontent">
			<li><a href="http://www.theme-junkie.com/demo/portal/2010/01/" title="January 2010">January 2010</a></li>
	<li><a href="http://www.theme-junkie.com/demo/portal/2009/12/" title="December 2009">December 2009</a></li>
	<li><a href="http://www.theme-junkie.com/demo/portal/2009/11/" title="November 2009">November 2009</a></li>
	<li><a href="http://www.theme-junkie.com/demo/portal/2008/12/" title="December 2008">December 2008</a></li>
	</ul>
	<ul style="display: none;" id="tag-cloud" class="tabcontent">

		<a href="http://www.theme-junkie.com/demo/portal/tag/2010/" class="tag-link-52" title="1 topic" style="font-size: 8pt;">2010</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/afghan/" class="tag-link-24" title="2 topics" style="font-size: 22pt;">Afghan</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/apple/" class="tag-link-16" title="2 topics" style="font-size: 22pt;">Apple</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/artillery/" class="tag-link-27" title="1 topic" style="font-size: 8pt;">artillery</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/banks/" class="tag-link-19" title="1 topic" style="font-size: 8pt;">banks</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/bill-clinton/" class="tag-link-14" title="1 topic" style="font-size: 8pt;">Bill Clinton</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/black-eyed/" class="tag-link-40" title="1 topic" style="font-size: 8pt;">Black Eyed</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/cameron/" class="tag-link-51" title="1 topic" style="font-size: 8pt;">Cameron</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/cheryl-cole/" class="tag-link-39" title="1 topic" style="font-size: 8pt;">Cheryl Cole</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/crozier/" class="tag-link-21" title="1 topic" style="font-size: 8pt;">Crozier</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/darling/" class="tag-link-18" title="1 topic" style="font-size: 8pt;">Darling</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/diabetes-risk/" class="tag-link-48" title="1 topic" style="font-size: 8pt;">diabetes risk</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/family-fat/" class="tag-link-47" title="1 topic" style="font-size: 8pt;">Family fat</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/ferdinand/" class="tag-link-35" title="1 topic" style="font-size: 8pt;">Ferdinand</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/ferrari/" class="tag-link-37" title="1 topic" style="font-size: 8pt;">Ferrari</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/fiorentina/" class="tag-link-31" title="1 topic" style="font-size: 8pt;">Fiorentina</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/flavanoid/" class="tag-link-45" title="1 topic" style="font-size: 8pt;">flavanoid</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/french-pm/" class="tag-link-28" title="1 topic" style="font-size: 8pt;">French PM</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/frivolous/" class="tag-link-36" title="1 topic" style="font-size: 8pt;">frivolous</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/gudjohnsen/" class="tag-link-34" title="1 topic" style="font-size: 8pt;">Gudjohnsen</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/haiti/" class="tag-link-15" title="1 topic" style="font-size: 8pt;">Haiti</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/ipad/" class="tag-link-17" title="2 topics" style="font-size: 22pt;">iPad</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/itv/" class="tag-link-20" title="1 topic" style="font-size: 8pt;">ITV</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/karzai/" class="tag-link-23" title="1 topic" style="font-size: 8pt;">Karzai</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/kristen-bell/" class="tag-link-41" title="1 topic" style="font-size: 8pt;">Kristen Bell</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/leukemia/" class="tag-link-46" title="1 topic" style="font-size: 8pt;">leukemia</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/mercedes/" class="tag-link-38" title="1 topic" style="font-size: 8pt;">Mercedes</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/mutu/" class="tag-link-32" title="1 topic" style="font-size: 8pt;">Mutu</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/nasa/" class="tag-link-49" title="1 topic" style="font-size: 8pt;">NASA</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/nintendo/" class="tag-link-44" title="1 topic" style="font-size: 8pt;">Nintendo</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/north-korea/" class="tag-link-26" title="1 topic" style="font-size: 8pt;">North Korea</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/obama/" class="tag-link-13" title="2 topics" style="font-size: 22pt;">Obama</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/rome/" class="tag-link-42" title="1 topic" style="font-size: 8pt;">Rome</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/sarkozy/" class="tag-link-29" title="1 topic" style="font-size: 8pt;">Sarkozy</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/shuttle-launch/" class="tag-link-50" title="1 topic" style="font-size: 8pt;">shuttle launch</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/tag-1/" class="tag-link-66" title="1 topic" style="font-size: 8pt;">tag #1</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tag2/" class="tag-link-67" title="1 topic" style="font-size: 8pt;">tag#2</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/taliban/" class="tag-link-30" title="1 topic" style="font-size: 8pt;">Taliban</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tottenham/" class="tag-link-33" title="1 topic" style="font-size: 8pt;">Tottenham</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tough-year/" class="tag-link-22" title="1 topic" style="font-size: 8pt;">tough year</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/wall-st/" class="tag-link-12" title="1 topic" style="font-size: 8pt;">Wall St</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/wii/" class="tag-link-43" title="1 topic" style="font-size: 8pt;">Wii</a>	</ul>
<script type="text/javascript">
	var tabs=new ddtabcontent("tabs")
	tabs.setpersist(false)
	tabs.setselectedClassTarget("link")
	tabs.init()
	</script>

</div> <!--end: tabber-->
<div class="clear"></div>
	<div class="fullwidget">
<h3>Hot Topics</h3><div class="clear"></div><div class="box"><div><a href="http://www.theme-junkie.com/demo/portal/tag/2010/" class="tag-link-52" title="1 topic" style="font-size: 8pt;">2010</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/afghan/" class="tag-link-24" title="2 topics" style="font-size: 22pt;">Afghan</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/apple/" class="tag-link-16" title="2 topics" style="font-size: 22pt;">Apple</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/artillery/" class="tag-link-27" title="1 topic" style="font-size: 8pt;">artillery</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/banks/" class="tag-link-19" title="1 topic" style="font-size: 8pt;">banks</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/bill-clinton/" class="tag-link-14" title="1 topic" style="font-size: 8pt;">Bill Clinton</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/black-eyed/" class="tag-link-40" title="1 topic" style="font-size: 8pt;">Black Eyed</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/cameron/" class="tag-link-51" title="1 topic" style="font-size: 8pt;">Cameron</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/cheryl-cole/" class="tag-link-39" title="1 topic" style="font-size: 8pt;">Cheryl Cole</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/crozier/" class="tag-link-21" title="1 topic" style="font-size: 8pt;">Crozier</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/darling/" class="tag-link-18" title="1 topic" style="font-size: 8pt;">Darling</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/diabetes-risk/" class="tag-link-48" title="1 topic" style="font-size: 8pt;">diabetes risk</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/family-fat/" class="tag-link-47" title="1 topic" style="font-size: 8pt;">Family fat</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/ferdinand/" class="tag-link-35" title="1 topic" style="font-size: 8pt;">Ferdinand</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/ferrari/" class="tag-link-37" title="1 topic" style="font-size: 8pt;">Ferrari</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/fiorentina/" class="tag-link-31" title="1 topic" style="font-size: 8pt;">Fiorentina</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/flavanoid/" class="tag-link-45" title="1 topic" style="font-size: 8pt;">flavanoid</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/french-pm/" class="tag-link-28" title="1 topic" style="font-size: 8pt;">French PM</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/frivolous/" class="tag-link-36" title="1 topic" style="font-size: 8pt;">frivolous</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/gudjohnsen/" class="tag-link-34" title="1 topic" style="font-size: 8pt;">Gudjohnsen</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/haiti/" class="tag-link-15" title="1 topic" style="font-size: 8pt;">Haiti</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/ipad/" class="tag-link-17" title="2 topics" style="font-size: 22pt;">iPad</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/itv/" class="tag-link-20" title="1 topic" style="font-size: 8pt;">ITV</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/karzai/" class="tag-link-23" title="1 topic" style="font-size: 8pt;">Karzai</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/kristen-bell/" class="tag-link-41" title="1 topic" style="font-size: 8pt;">Kristen Bell</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/leukemia/" class="tag-link-46" title="1 topic" style="font-size: 8pt;">leukemia</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/mercedes/" class="tag-link-38" title="1 topic" style="font-size: 8pt;">Mercedes</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/mutu/" class="tag-link-32" title="1 topic" style="font-size: 8pt;">Mutu</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/nasa/" class="tag-link-49" title="1 topic" style="font-size: 8pt;">NASA</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/nintendo/" class="tag-link-44" title="1 topic" style="font-size: 8pt;">Nintendo</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/north-korea/" class="tag-link-26" title="1 topic" style="font-size: 8pt;">North Korea</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/obama/" class="tag-link-13" title="2 topics" style="font-size: 22pt;">Obama</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/rome/" class="tag-link-42" title="1 topic" style="font-size: 8pt;">Rome</a>

<a href="http://www.theme-junkie.com/demo/portal/tag/sarkozy/" class="tag-link-29" title="1 topic" style="font-size: 8pt;">Sarkozy</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/shuttle-launch/" class="tag-link-50" title="1 topic" style="font-size: 8pt;">shuttle launch</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tag-1/" class="tag-link-66" title="1 topic" style="font-size: 8pt;">tag #1</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tag2/" class="tag-link-67" title="1 topic" style="font-size: 8pt;">tag#2</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/taliban/" class="tag-link-30" title="1 topic" style="font-size: 8pt;">Taliban</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tottenham/" class="tag-link-33" title="1 topic" style="font-size: 8pt;">Tottenham</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/tough-year/" class="tag-link-22" title="1 topic" style="font-size: 8pt;">tough year</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/wall-st/" class="tag-link-12" title="1 topic" style="font-size: 8pt;">Wall St</a>
<a href="http://www.theme-junkie.com/demo/portal/tag/wii/" class="tag-link-43" title="1 topic" style="font-size: 8pt;">Wii</a></div>

</div>  	</div> <!--end: fullwidget-->
  	<div class="leftwidget">
		<h3>Pages</h3><div class="clear"></div><div class="box">		<ul>
			<li class="page_item page-item-2"><a href="http://www.theme-junkie.com/demo/portal/about/" title="About">About</a></li>
<li class="page_item page-item-29"><a href="http://www.theme-junkie.com/demo/portal/elements/" title="Elements">Elements</a></li>
<li class="page_item page-item-31"><a href="http://www.theme-junkie.com/demo/portal/page-templates/" title="Page Templates">Page Templates</a>
<ul>

	<li class="page_item page-item-39"><a href="http://www.theme-junkie.com/demo/portal/page-templates/links/" title="Links">Links</a></li>
</ul>
</li>
<li class="page_item page-item-43"><a href="http://www.theme-junkie.com/demo/portal/theme-options/" title="Theme Options">Theme Options</a></li>
		</ul>
		</div>  	</div> <!--end: leftwidget-->
  	<div class="rightwidget">
    	<h3>Blogroll</h3><div class="clear"></div><div class="box">

	<ul class="xoxo blogroll">
<li><a href="http://wordpress.org/development/">Development Blog</a></li>
<li><a href="http://codex.wordpress.org/">Documentation</a></li>
<li><a href="http://wordpress.org/extend/plugins/">Plugins</a></li>
<li><a href="http://wordpress.org/extend/ideas/">Suggest Ideas</a></li>
<li><a href="http://wordpress.org/support/">Support Forum</a></li>

	</ul>
</div>
  	</div> <!--end: rightwidget-->

</div> <!--end: sidebar-->


<div class="clear"></div>
</div> <!--end: wrapper-->




HTML;
