<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

Tpl::_get()->parse('register','register');

page_footer();
output(Tpl::_get()->output());
