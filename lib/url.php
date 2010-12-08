<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

define('inc','/');

class Url {
	
	static $urls = array(
		'home',
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
	
	public static function games(){
		return self::prep().inc.'index.php?act=games';
	}
	
	public static function game_list(){
		return self::games().'&do=list';
	}

	public static function game_info(){
		return self::games().'&do=info';
	}
	
	public static function game_play(){
		return self::games().'&do=play';
	}
	
	public static function traffic(){
		return self::prep().inc.'index.php?act=traffic';
	}
	
	public static function traffic_stats(){
		return self::traffic().'&do=stats';
	}
	
	public static function page(){
		return self::prep().inc.'index.php?act=pages&page=';
	}

	public static function profile(){
		return self::prep().inc.'index.php?act=profile';
	}

}
