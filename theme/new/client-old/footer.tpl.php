<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['footer'] = <<<HTML
	<div style="clear:both;"></div>
</div>
<div id="footerwrap">
	Footer
</div>

<div id="footer">
	{seo_text}
</div>

<div class="performance">
	{performance}
</div>

</body>
</html>
HTML;

?>
