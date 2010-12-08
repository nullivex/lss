<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
page_header();

Tpl::_get()->parse('game_play','game_play');

page_footer();
output(Tpl::_get()->output());
