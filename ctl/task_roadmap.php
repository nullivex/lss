<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/tasks.php');
page_header();

//task roadmap
switch(get('base')){
	case 'year':
		$list = Tasks::_get()->taskRoadmap(mktime(0,0,0,1,1));
		break;
	
	case 'month':
		$list = Tasks::_get()->taskRoadmap(strtotime('this month'));
		break;

	case 'biweek':
		$list = Tasks::_get()->taskRoadmap(strtotime('last week'));
		break;

	case 'week':
		$list = Tasks::_get()->taskRoadmap(strtotime('this week'));
		break;
	
	case 'yesterday':
		$list = Tasks::_get()->taskRoadmap(strtotime('yesterday'),mktime(0,0,0));
		break;
	
	case 'today':
	default:
		$list = Tasks::_get()->taskRoadmap(strtotime('today'));
		break;
}

$html = '';
$count = 1;
$hours = 0;
$age = 0;
foreach($list as $task){
	$params = $task;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['age'] = age($task['start'],$task['end']);
	$params['start'] = date(Config::get('date','start_format'),$task['start']);
	$params['end'] = date(Config::get('date','end_format'),$task['end']);
	$html .= Tpl::_get()->parse('tasks','task_row_roadmap',$params,true);
	$count++; $hours += $task['duration']; $age += $task['end'] - $task['start'];
}
$params = array();
$params['name'] = 'Recently Completed Tasks';
$params['tasks'] = $html;
$params['url_today'] = Url::task_roadmap_time('today');
$params['url_yesterday'] = Url::task_roadmap_time('yesterday');
$params['url_week'] = Url::task_roadmap_time('week');
$params['url_biweek'] = Url::task_roadmap_time('biweek');
$params['url_month'] = Url::task_roadmap_time('month');
$params['url_year'] = Url::task_roadmap_time('year');
$params['task_count'] = $count;
$params['task_allotted'] = $hours;
$params['task_age'] = hours($age);
Tpl::_get()->parse('tasks','tasks_roadmap',$params);

page_footer();
output(Tpl::_get()->output());
