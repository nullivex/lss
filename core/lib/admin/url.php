<?php
/*
 * LSS Core
 * OpenLSS - Light, sturdy, stupid simple
 * 2010 Nullivex LLC, All Rights Reserved.
 * Bryan Tong <contact@nullivex.com>
 *
 *   OpenLSS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   OpenLSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenLSS.  If not, see <http://www.gnu.org/licenses/>.
 */

define('inc','/');

class Url {

	static $urls = array(
		'home',
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
