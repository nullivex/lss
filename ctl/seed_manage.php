<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/seed.php');
Login::requireManager();
page_header();

if(post('create')){
	try {
		Seed::_get()->create(post());
		alert('seed added successfully',true,true);
		redirect(Url::seed());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('update')){
	try {
		Seed::_get()->update(post());
		alert('seeds updated successfully',true,true);
		redirect(Url::seed());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('action')){
	try {
		switch(post('action')){
			case 'delete':
			default:
				foreach(post('seed') as $seed){
					if(isset($seed['action'])) Seed::_get()->delete($seed);
				}
				alert('tasks deleted successfully',true,true);
				redirect(Url::seed());
				break;
		}
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

//seed management
$list = Seed::_get()->seedList();
$html = '';
$count = 1;
foreach($list as $seed){
	$params = $seed;
	$params['row_class'] = ($count % 2) ? 'odd' : 'even';
	$params['assigned'] = Seed::assigned($seed['seed_id'],$seed['staff_id']);
	$html .= Tpl::_get()->parse('seed','seed_row',$params,true);
	$count++;
}
$params = Seed::_get()->createParams();
$params['assign'] = Seed::assigned();
$params['add'] = Tpl::_get()->parse('seed','create',$params,true);
$params['tasks'] = $html;
Tpl::_get()->parse('seed','seed',$params);

page_footer();
output(Tpl::_get()->output());
