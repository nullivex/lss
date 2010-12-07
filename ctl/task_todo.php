<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/tasks.php');

page_header();

if(post('create')){
	try {
		Tasks::_get()->createTodo(post());
		alert('todo created successfully',true,true);
		redirect(Url::task_todo(get('task_id')));
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('close')){
	try {
		Tasks::_get()->closeTodo(post());
		alert('todo closed successfully',true,true);
		redirect(Url::task_todo(get('task_id')));
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('open')){
	try {
		Tasks::_get()->openTodo(post());
		alert('todo opened successfully',true,true);
		redirect(Url::task_todo(get('task_id')));
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$task = Tasks::_get()->manageParams(get('task_id'));
$list = Tasks::_get()->todoList(get('task_id'));

$html = '';
$count = 0;
foreach($list as $todo){
	$params = $todo;
	$params['url_task_todo'] = Url::task_todo(get('task_id'));
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['status'] = $todo['is_complete'] ? 'Closed' : 'Open';
	$params['button'] = $todo['is_complete'] ? 'Open' : 'Close';
	$params['action'] = $todo['is_complete'] ? 'open' : 'close';
	$params['color'] = $todo['is_complete'] ? '#333' : 'green';
	$html .= Tpl::_get()->parse('tasks','task_row_todo',$params,true);
	$count++;
}

$params = Tasks::_get()->createTodoParams(get('task_id'));
$params['add'] = Tpl::_get()->parse('tasks','create_todo',$params,true);
$params['url_task_todo'] = Url::task_todo(get('task_id'));
$params['name'] = $task['desc'];
$params['tasks'] = $html;

Tpl::_get()->parse('tasks','task_todo',$params);

page_footer();
output(Tpl::_get()->output());
