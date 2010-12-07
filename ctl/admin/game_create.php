<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
require_once(ROOT.'/lib/categories.php');
page_header();

if(post('create')){
	try {
		Games::_get()->create(post());
		alert('game added successfully',true,true);
		redirect(Url::games());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Games::_get()->createParams();
$params = array_merge($params,post());
$params['categories'] = Categories::_get()->categoryDrop('category_id',$params['category_id']);
$params['is_featured'] = $params['is_featured'] ? 'checked="checked"' : '';
$params['is_tradable'] = $params['is_tradable'] ? 'checked="checked"' : '';
$params['is_active'] = $params['is_active'] ? 'checked="checked"' : '';
Tpl::_get()->parse('games','create',$params);

page_footer();
output(Tpl::_get()->output());
