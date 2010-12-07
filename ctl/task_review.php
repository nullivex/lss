<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/tasks.php');

Login::requireManager();
page_header();

if(post('update')){
	try {
		Tasks::_get()->update($_POST);
		alert('tasks updated successfully',true,true);
		redirect(Url::task_review());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('action')){
	try {
		switch(post('action')){
			case 'review':
				Tasks::_get()->review($_POST);
				alert('tasks reviewed successfully',true,true);
				redirect(Url::task_review());
			break;
			case 'unfinish':
				Tasks::_get()->finish($_POST,false);
				alert('tasks unfinished successfully',true,true);
				redirect(Url::task_review());
			break;
			case 'delete':
				Tasks::_get()->delete($_POST);
				alert('tasks deleted successfully',true,true);
				redirect(Url::task_review());
			break;
		}
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$list = Tasks::_get()->taskList(false,true);

$html = '';
foreach($list as $task){
	$params = $task;
	$params['age'] = age($task['start']);
	$params['start'] = date(Config::get('date','review_format'),$task['start']);
	$params['end'] = date(Config::get('date','review_format'),$task['end']);
	$params['age'] = age($task['start'],$task['end']);
	$params['assigned'] = Tasks::assigned($task['task_id'],$task['staff_id']);
	$html .= Tpl::_get()->parse('tasks','task_review_row',$params,true);
}

$params = Tasks::_get()->createParams();
$params['add'] = '';
$params['tasks'] = $html;

Tpl::_get()->parse('tasks','task_review',$params);

page_footer();
output(Tpl::_get()->output());
