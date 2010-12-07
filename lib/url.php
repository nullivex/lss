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
		'tasks',
		'task_create',
		'task_review',
		'task_reports',
		'task_roadmap',
		'staff',
		'staff_create',
		'seed',
		'manage',
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

	public static function tasks(){
		return self::prep().inc.'index.php?act=tasks';
	}

	public static function task_create(){
		return self::tasks().'&do=create';
	}

	public static function task_manage($task_id){
		return self::tasks().'&do=manage&task_id='.$task_id;
	}

	public static function task_review(){
		return self::tasks().'&do=review';
	}

	public static function task_reports(){
		return self::tasks().'&do=reports';
	}

	public static function task_roadmap(){
		return self::tasks().'&do=roadmap';
	}

	public static function tasK_roadmap_time($time='today'){
		return self::task_roadmap().'&base='.$time;
	}

	public static function task_todo($task_id){
		return self::tasks().'&do=todo&task_id='.$task_id;
	}

	public static function staff(){
		return self::prep().'index.php?act=staff';
	}

	public static function staff_create(){
		return self::staff().'&do=create';
	}

	public static function staff_manage($staff_id){
		return self::staff().'&do=manage&staff_id='.$staff_id;
	}

	public static function seed(){
		return self::prep().inc.'index.php?act=seed';
	}

	public static function manage(){
		return self::prep().inc.'index.php?manage=true';
	}

	public static function profile(){
		return self::prep().inc.'index.php?act=profile';
	}
	
	public static function login(){
		return self::prep().inc.'index.php?login=true';
	}

	public static function logout(){
		return self::prep().inc.'index.php?logout=true';
	}

}
