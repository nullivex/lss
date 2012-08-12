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

class Members {

	public $db;

	public static function _get(){
		return new Members();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function memberList(){
		$query = $this->db->prepare('select * from members where is_active = ?');
		$query->execute(array(1));
		return $query->fetchAll();
	}

	public function createParams(){
		return array(
			'username'		=>	'',
			'email'			=>	'',
			'is_affiliate'	=>	''
		);
	}

	public function editParams($member_id){
		$query = $this->db->prepare('select * from members where member_id = ? and is_active = ?');
		$query->execute(array($member_id,1));
		$result = $query->fetch();
		if(empty($result['member_id'])) throw new Exception("member: no staff member found");
		return $result;
	}

	public static function validate($data,$password=true){
		Validate::prime($data);
		Validate::go('username')->not('blank')->min(4)->max(16)->type('username');
		if($password) Validate::go('password')->not('blank')->min(6)->max(20)->type('password');
		Validate::go('email')->not('blank')->type('email');
		Validate::paint();
	}

	public function create($data){
		data($data);
		self::validate($data);
		if(data('password') != data('confirm_password')) throw new Exception('member: password mismatch');
		$affiliate = data('is_affiliate') ? 1 : 0;
		$query = $this->db->prepare('insert into members(username,`password`,email,is_affiliate)values(?,?,?,?)');
		$query->execute(array(data('username'),md5(data('password')),data('email'),$affiliate));
		return $this->db->lastInsertId();
	}

	public function register($data){
		data($data);
		self::validate($data);
		if(data('password') != data('confirm_password')) throw new Exception('member: password mismatch');
		$query = $this->db->prepare('insert into members(username,`password`,email)values(?,?,?)');
		$query->execute(array(data('username'),md5(data('password')),data('email')));
		return $this->db->lastInsertId();
	}

	public function edit($data){
		data($data);
		self::validate($data,false);
		if(data('password') && data('confirm_password')){
			if(data('password') != data('confirm_password')) throw new Exception('member: password mismatch');
			$query = $this->db->prepare('update members set `password` = ? where member_id = ?');
			$query->execute(array(md5(data('password')),data('member_id')));
		}
		$affiliate = data('is_affiliate') ? 1 : 0;
		$query = $this->db->prepare('update members set username = ?, email = ?, is_affiliate = ? where member_id = ?');
		$query->execute(array(data('username'),data('email'),$affiliate,data('member_id')));
		return data('member_id');
	}

	public function delete($data){
		data($data);
		Validate::prime($data);
		Validate::go('staff_id')->not('blank')->type('num');
		Validate::go('confirm_delete')->not('blank');
		Validate::paint();
		$query = $this->db->prepare('update member set is_active = ? where member_id = ?');
		$query->execute(array(0,data('member_id')));
		return data('member_id');
	}

	public function profile($data){
		if(!isset($data['member_id'])) throw new Exception('members: no member id');
		if(!isset($data['username'])) throw new Exception('members: no username');
		if(!isset($data['email'])) throw new Exception('members: no email');
		if(
			isset($data['password']) && isset($data['confirm_password']) &&
			!empty($data['password']) && !empty($data['confirm_password'])
		){
			if($data['password'] != $data['confirm_password']) throw new Exception('members: password mismatch');
			$query = $this->db->prepare('update members set `password` = ? where member_id = ?');
			$query->execute(array(md5($data['password']),$data['member_id']));
			//update session
			Login::updateLivePassword(md5($data['password']));
		}
		$query = $this->db->prepare('update members set username = ?, email = ? where member_id = ?');
		$query->execute(array($data['username'],$data['email'],$data['member_id']));
		return $data['staff_id'];
	}

}
