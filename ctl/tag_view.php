<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/tags.php');

$tag = Tags::_get()->get(get('tag_id'));
redirect(Url::search_all($tag['tag']));
