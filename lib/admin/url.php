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
		'traffic',
		'categories',
		'category_create',
		'games',
		'game_create',
		'staff',
		'staff_create',
		'pages',
		'page_create',
		'members',
		'member_create',
		'profile',
		'login',
		'logout'
	);

	public static function prep(){
		return Config::get('url','url');
	}

	public static function _all(){
		$urls = array();
		foreach(self::$urls as $func) $urls['url_'.$func] = self::$func();
		return $urls;
	}

	public static function home(){
		return self::prep().inc.'admin.php';
	}

	public static function games(){
		return self::prep().'admin.php?act=games';
	}

	public static function game_create(){
		return self::games().'&do=create';
	}

	public static function game_manage($game_id){
		return self::games().'&do=manage&game_id='.$game_id;
	}

	public static function game_status($status){
		return self::games().'&do=list&status='.$status;
	}

	public static function categories(){
		return self::prep().'admin.php?act=categories';
	}

	public static function category_create(){
		return self::categories().'&do=create';
	}

	public static function category_manage($category_id){
		return self::categories().'&do=manage&category_id='.$category_id;
	}

	public static function pages(){
		return self::prep().'admin.php?act=pages';
	}

	public static function page_create(){
		return self::pages().'&do=create';
	}

	public static function page_manage($page_id){
		return self::pages().'&do=manage&page_id='.$page_id;
	}

	public static function members(){
		return self::prep().'admin.php?act=members';
	}

	public static function member_create(){
		return self::members().'&do=create';
	}

	public static function member_manage($member_id){
		return self::members().'&do=manage&member_id='.$member_id;
	}
	
	public static function traffic(){
		return self::prep().'admin.php?act=traffic';
	}

	public static function staff(){
		return self::prep().'admin.php?act=staff';
	}

	public static function staff_create(){
		return self::staff().'&do=create';
	}

	public static function staff_manage($staff_id){
		return self::staff().'&do=manage&staff_id='.$staff_id;
	}

	public static function profile(){
		return self::prep().inc.'admin.php?act=profile';
	}

	public static function login(){
		return self::prep().inc.'admin.php?login=true';
	}

	public static function logout(){
		return self::prep().inc.'admin.php?logout=true';
	}

}
