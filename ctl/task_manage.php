<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/tasks.php');

page_header();

if(post('create')){
	try {
		Tasks::_get()->create($_POST);
		alert('task added successfully',true,true);
		redirect(Url::tasks());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('update')){
	try {
		Tasks::_get()->update($_POST);
		alert('tasks updated successfully',true,true);
		redirect(Url::tasks());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('action')){
	try {
		switch(post('action')){
			case 'start':
				Tasks::_get()->start(post());
				alert('tasks started successfully',true,true);
				redirect(Url::tasks());
				break;
			case 'finish':
				Tasks::_get()->finish(post());
				alert('tasks finished successfully',true,true);
				redirect(Url::tasks());
				break;
			case 'delete':
				Tasks::_get()->delete(post());
				alert('tasks deleted successfully',true,true);
				redirect(Url::tasks());
				break;
		}
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

//task management
if(Login::manage()) $list = Tasks::_get()->taskList();
else $list = Tasks::_get()->taskList(Login::staffId());
$html = '';
$count = 1;
foreach($list as $task){
	$params = $task;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['age'] = $task['start'] ? hours(time()-$task['start']) : 'pending';
	$params['start'] = date(Config::get('date','review_format'),$task['start']);
	$params['assigned'] = Login::manage() ? Tasks::assigned($task['task_id'],$task['staff_id']) : $task['username'];
	$params['url_task_todo'] = Url::task_todo($task['task_id']);
	$tpl = Login::manage() ? 'task_row_manage' : 'task_row';
	$html .= Tpl::_get()->parse('tasks',$tpl,$params,true);
	$count++;
}
$params = Tasks::_get()->createParams();
$params['assign'] = Tasks::assigned();
if(Login::manage()){
	$params['add'] = Tpl::_get()->parse('tasks','create',$params,true);
} else {
	$params['staff_id'] = Login::staffId();
	$params['add'] = Tpl::_get()->parse('tasks','create_short',$params,true);
}
$params['tasks'] = $html;
Tpl::_get()->parse('tasks','tasks',$params);

//tasks awaiting review
$list = Tasks::_get()->taskList(Login::staffId(),true,false);
$html = '';
$count = 1;
foreach($list as $task){
	$params = $task;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['age'] = hours($task['end']-$task['start']);
	$params['start'] = date(Config::get('date','start_format'),$task['start']);
	$params['end'] = date(Config::get('date','end_format'),$task['end']);
	$html .= Tpl::_get()->parse('tasks','task_row_short',$params,true);
	$count++;
}
$params = array();
$params['name'] = 'Your Tasks Awaiting Review';
$params['tasks'] = $html;
Tpl::_get()->parse('tasks','tasks_short',$params);

//tasks closed
$list = Tasks::_get()->taskList(Login::staffId(),true,true,true,10);
$html = '';
$count = 1;
foreach($list as $task){
	$params = $task;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['age'] = age($task['start'],$task['end']);
	$params['start'] = date(Config::get('date','start_format'),$task['start']);
	$params['end'] = date(Config::get('date','end_format'),$task['end']);
	$html .= Tpl::_get()->parse('tasks','task_row_short',$params,true);
	$count++;
}
$params = array();
$params['name'] = 'Your Recently Completed Tasks';
$params['tasks'] = $html;
Tpl::_get()->parse('tasks','tasks_short',$params);

page_footer();
output(Tpl::_get()->output());
