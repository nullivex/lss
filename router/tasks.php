<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

switch(get('do')){

	case 'todo':
		require_once(ROOT.'/ctl/task_todo.php');
		break;
	
	case 'review':
		require_once(ROOT.'/ctl/task_review.php');
		break;

	case 'reports':
		require_once(ROOT.'/ctl/task_reports.php');
		break;

	case 'roadmap':
		require_once(ROOT.'/ctl/task_roadmap.php');
		break;

	default:
	case 'manage':
		require_once(ROOT.'/ctl/task_manage.php');
		break;

}