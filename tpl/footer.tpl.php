<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

$tpl = array();

$tpl['footer'] = <<<HTML
<div class="clear"></div>
</div> <!--end: wrapper-->

<div id="bottom">
	&copy; {cur_year} <a href="{url_home}">{site_name}</a>
	<small>{script_stats}</small>
	<div class="clear"></div>
</div> <!--end: bottom-->
</body></html>
HTML;

