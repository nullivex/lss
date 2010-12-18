<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

define('inc','/');

class Url {
	
	static $urls = array(
		'home',
		'tags',
		'games',
		'game_list',
		'game_info',
		'game_play',
		'search',
		'traffic',
		'traffic_stats',
		'account',
		'page',
		'register',
		'profile',
		'login',
		'logout'
	);

	public static function prep(){
		return Config::get('url','url');
	}

	public static function _all(){
		$urls = array();
		foreach(self::$urls as $func){
			$urls['url_'.$func] = self::$func();
		}
		return $urls;
	}

	public static function home(){
		return self::prep().inc.'index.php';
	}
	
	public static function search(){
		return self::prep().inc.'index.php?act=search';
	}

	public static function search_all($q){
		return self::search().'&do=all&q='.$q;
	}

	public static function account(){
		return self::prep().inc.'index.php?act=account';
	}
	
	public static function register(){
		return self::account().'&do=register';
	}
	
	public static function login(){
		return self::account().'&do=login';
	}	
	
	public static function logout(){
		return self::account().'&do=logout';
	}

	public static function tags(){
		return self::prep().inc.'index.php?act=tags';
	}

	public static function tag($tag_id){
		return self::tags().'&do=view&tag_id='.$tag_id;
	}
	
	public static function games(){
		return self::prep().inc.'index.php?act=games';
	}
	
	public static function game_list(){
		return self::games().'&do=list';
	}

	public static function game_list_category($category_id){
		return self::game_list().'&category_id='.$category_id;
	}

	public static function game_info($game_id=null){
		return self::games().'&do=info&game_id='.$game_id;
	}

	public static function game_info_short($type,$game_id){
		return self::games().'&do=info_short&game_id='.$game_id.'&type='.$type;
	}
	
	public static function game_play($game_id=null){
		return self::games().'&do=play&game_id='.$game_id;
	}
	
	public static function traffic(){
		return self::prep().inc.'index.php?act=traffic';
	}
	
	public static function traffic_stats(){
		return self::traffic().'&do=stats';
	}

	public static function ad_out($ad_id){
		return self::prep().inc.'index.php?act=ads&do=out&ad_id='.$ad_id;
	}
	
	public static function page(){
		return self::prep().inc.'index.php?act=pages&page=';
	}

	public static function profile(){
		return self::prep().inc.'index.php?act=profile';
	}

}
