<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

Tpl::_get()->parse('game_list','game_list');

page_footer();
output(Tpl::_get()->output());
