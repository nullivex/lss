<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require('api_common.php');
require_once(ROOT.'/lib/seed.php');

try {
	Seed::_get()->seedTasks();
	apiOutput('success');
} catch (Exception $e){
	apiOutput($e->getMessage());
}

