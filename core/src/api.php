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

function apiCreds($data){
	if(!isset($data['api_username'])) throw new Exception("api: user not found");
	if(!isset($data['api_password'])) throw new Exception("api: password not found");
	return array($data['api_username'],$data['api_password']);
}

function apiAuth($username,$password){
	if(!Config::get('api','enabled')) throw new Exception("api: api disabled");
	if($username != Config::get('api','username')) throw new Exception("api: username invalid");
	if($password != Config::get('api','password')) throw new Exception("api: password invalid");
}

function apiError($msg){
	echo trim($msg);
	exit;
}

function apiOutput($out){
	echo trim($out);
	flush();
}

function crawlerOutput($out){
	echo trim($out)."\n";
	flush();
}
