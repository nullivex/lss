<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/pages.php');
page_header();

data(Pages::_get()->view(get('page')));
Tpl::_get()->parse('pages','view',data());
Tpl::_get()->setConstant('site_title',data('name').SITE_TITLE);

page_footer();
output(Tpl::_get()->output());
