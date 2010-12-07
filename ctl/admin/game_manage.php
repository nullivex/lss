<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

require_once(ROOT.'/lib/games.php');
require_once(ROOT.'/lib/categories.php');
page_header();

if(post('copy')){
	try {
		Games::_get()->create(post());
		alert('game created successfully',true,true);
		redirect(Url::games());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('edit')){
	try {
		Games::_get()->edit(post());
		alert('game updated successfully',true,true);
		redirect(Url::games());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

if(post('delete')){
	try {
		Games::_get()->delete(post());
		alert('game deleted successfully',true,true);
		redirect(Url::games());
	} catch (Exception $e){
		alert($e->getMessage(),false);
	}
}

$params = Games::_get()->editParams(get('game_id'));
$params = array_merge($params,post());
$params['categories'] = Categories::_get()->categoryDrop('category_id',$params['category_id']);
$params['is_featured'] = $params['is_featured'] ? 'checked="checked"' : '';
$params['is_tradable'] = $params['is_tradable'] ? 'checked="checked"' : '';
$params['is_active'] = $params['is_active'] ? 'checked="checked"' : '';
$params['url_game_manage'] = Url::game_manage(get('game_id'));
Tpl::_get()->parse('games','edit',$params);

page_footer();
output(Tpl::_get()->output());
