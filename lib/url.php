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

	public static function page(){
		return self::prep().inc.'index.php?act=pages&page=';
	}

	public static function profile(){
		return self::prep().inc.'index.php?act=profile';
	}

	public static function register(){
		return self::prep().inc.'index.php?register=true';
	}
	
	public static function login(){
		return self::prep().inc.'index.php?login=true';
	}

	public static function logout(){
		return self::prep().inc.'index.php?logout=true';
	}

}
