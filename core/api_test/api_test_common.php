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

date_default_timezone_set('America/Chicago');

function auth($username,$password){
	return array(
		'api_username'	=>	urlencode($username),
		'api_password'	=>	urlencode($password)
	);
}

function send($url,$data,$post=false){

	$curl = curl_init();

	//Enable Redirection/Cookies
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION,true);
	
	//Disable SSL Verification
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

	//Return Data
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	if($post){
		$post_data = prepare($data,true);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$post_data);
	} else {
		$get_data = $url.prepare($data);
		curl_setopt($curl, CURLOPT_URL, $get_data);
	}

	return curl_exec($curl);

}

function prepare($data,$post=false){
	$out = '';
	foreach($data as $name => $val){
		if($post) $inc = isset($inc) ? '&' : '';
		else $inc = isset($inc) ? '&' : '?';
		$out .= $inc.$name.'='.urlencode($val);
	}
	return $out;
}
