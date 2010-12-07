<?php
//----------------------------------------------
//Sloppy Games
//	(c) 2009 eSited Solutions, Nullivex LLC
//	$Id: config.php 11 2009-12-25 05:52:53Z sigma $
//----------------------------------------------

$templates = array();

$templates['pagination'] = <<<HTML
	<div class="pagination">
		{previous}
		{first_page}
		{pages}
		{last_page}
		{next}
	</div>
HTML;

$templates['previous'] = <<<HTML
	<a href="{url}"><< Previous</a>
HTML;

$templates['first_page'] = <<<HTML
	<a href="{url}">1</a> ... 
HTML;

$templates['page'] = <<<HTML
	<a href="{url}">{page}</a>
HTML;

$templates['page_selected'] = <<<HTML
	<a href="{url}"><strong>{page}</strong></a>
HTML;

$templates['last_page'] = <<<HTML
	 ... <a href="{url}">{page}</a>
HTML;

$templates['next'] = <<<HTML
	<a href="{url}">Next >></a>
HTML;

?>
