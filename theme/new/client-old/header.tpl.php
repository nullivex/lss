<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['header'] = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta name="description" content="{meta_desc}" />
<meta name="keywords" content="{meta_keywords}" />
<meta name="google-site-verification" content="c0eXf-8__KcNRIqiLnsyZ5f5jAPyPkQspTaJ1A3JZO8" />
<title>{page_title}</title>

<link href="{skin_url}/style.css" type="text/css" rel="stylesheet" />
<link href="{skin_url}/columns.css" type="text/css" rel="stylesheet" />
<link href="{skin_url}/extra.css" type="text/css" rel="stylesheet" />

<link rel="stylesheet" type="text/css" href="{site_url}/lead/js/shadowbox/shadowbox.css">
<script type="text/javascript" src="{site_url}/lead/js/jquery.js"></script>
<script type="text/javascript" src="{site_url}/lead/js/shadowbox/shadowbox.js"></script>
<script type="text/javascript">
Shadowbox.init({
	players: ["img","html","iframe"]
});
</script>


<script type="text/javascript">var baseDir = '{uri}';</script>
<script src="{skin_url}files/javascript.js" type="text/javascript"></script>
<style type="text/css" media="screen">

/* Begin Contact Form ][ CSS */
.contactform {
	position: static;
	overflow: hidden;
}

.contactleft {
	width: 25%;
	text-align: right;
	clear: both;
	float: left;
	display: inline;
	padding: 4px;
	margin: 5px 0;
    font-weight: bold;
}

.contactright {
	width: 70%;
	text-align: left;
	float: right;
	display: inline;
	padding: 4px;
	margin: 5px 0;
}

.contacterror {
	border: 2px solid #ff0000;
}
/* End Contact Form ][ CSS */

	</style>
</head>

<body>

<script type="text/javascript">
function clearDefault(el) {
	if (el.defaultValue==el.value) el.value = ""
}
</script>

<div id="header">
	<table align="center" border="0" cellpadding="0" cellspacing="0">
	<tbody>
	<tr>
		<td rowspan="3" align="left" valign="top" width="191">
			<a href="{base_url}">
				<img class="main-logo" src="{skin_url}/images/spacer.gif" alt="{site_name}" border="0" width="420" height="58">
			</a>
		</td>
		<td style="padding: 0px 10px 0px 0px;" align="right" valign="middle" width="100%">
			<form action="/index.php" id="cse-search-box" method="get">
			<div>
				<input type="hidden" name="a" value="search" />
				<input type="hidden" name="cx" value="016640430065768518970:v0uy3efajla" />
				<input name="cof" value="FORID:9" type="hidden">
				<input name="ie" value="ISO-8859-1" type="hidden">
				<input type="text" name="q" size="22" style="background:transparent;" />
				<input type="submit" id="searchsubmit" value="Search" />
			</div>
			</form>
		</td>
	</tr>
	</tbody>
	</table>

	<div class="top-Menu" id="catmenu">
		<ul>
			{top_nav}
		</ul>
	</div>
</div>

<div style="height:90px;background:#ffffff;width:972px;margin: 0 auto;padding:4px;">
<table>
<tr>
<td align="center" valign="middle" style="height:90px;">
<img src="http://www.net-games.biz/thumbs/39c85eb93bf4.jpg" width="115" height="90" />
</td>
<td>
<img src="http://www.webpencil.com/banneroutlines/728x90.gif" />
</td>
<td align="center" valign="middle" style="height:90px;">
<img src="http://www.net-games.biz/thumbs/6ab440cb7d9a.jpg" width="115" height="90" />
</td>
</tr>
</table>
</div>

<div id="wrap">
HTML;

?>
