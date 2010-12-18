<?php
/*
 * Simple CMS
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Staff {

	public $db;

	public static function _get(){
		return new Staff();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function staffList(){
		$query = $this->db->prepare('select * from staff where is_active = ?');
		$query->execute(array(1));
		return $query->fetchAll();
	}

	public function createParams(){
		return array(
			'username'		=>	'',
			'email'			=>	'',
			'is_manager'	=>	''
		);
	}

	public function editParams($staff_id){
		$query = $this->db->prepare('select * from staff where staff_id = ? and is_active = ?');
		$query->execute(array($staff_id,1));
		$result = $query->fetch();
		if(empty($result['staff_id'])) throw new Exception("staff: no staff member found");
		return $result;
	}

	public function create($data){
		if(!isset($data['username'])) throw new Exception('staff: no username');
		if(!isset($data['email'])) throw new Exception('staff: no email');
		if(!isset($data['password']) || !isset($data['confirm_password'])) throw new Exception('staff: no password');
		if($data['password'] != $data['confirm_password']) throw new Exception('staff: password mismatch');
		$manager = isset($data['is_manager']) ? 1 : 0;
		$query = $this->db->prepare('insert into staff(username,`password`,email,is_manager)values(?,?,?,?)');
		$query->execute(array($data['username'],md5($data['password']),$data['email'],$manager));
		return $this->db->lastInsertId();
	}

	public function edit($data){
		if(!isset($data['staff_id'])) throw new Exception('staff: no staff id');
		if(!isset($data['username'])) throw new Exception('staff: no username');
		if(!isset($data['email'])) throw new Exception('staff: no email');
		if(
			isset($data['password']) && isset($data['confirm_password']) &&
			!empty($data['password']) && !empty($data['confirm_password'])
		){
			if($data['password'] != $data['confirm_password']) throw new Exception('staff: password mismatch');
			$query = $this->db->prepare('update staff set `password` = ? where staff_id = ?');
			$query->execute(array(md5($data['password']),$data['staff_id']));
			//update session
			Login::updateLivePassword(md5($data['password']));
		}
		$manager = isset($data['is_manager']) ? 1 : 0;
		$query = $this->db->prepare('update staff set username = ?, email = ?, is_manager = ? where staff_id = ?');
		$query->execute(array($data['username'],$data['email'],$manager,$data['staff_id']));
		return $data['staff_id'];
	}

	public function profile($data){
		if(!isset($data['staff_id'])) throw new Exception('staff: no staff id');
		if(!isset($data['username'])) throw new Exception('staff: no username');
		if(!isset($data['email'])) throw new Exception('staff: no email');
		if(
			isset($data['password']) && isset($data['confirm_password']) &&
			!empty($data['password']) && !empty($data['confirm_password'])
		){
			if($data['password'] != $data['confirm_password']) throw new Exception('staff: password mismatch');
			$query = $this->db->prepare('update staff set `password` = ? where staff_id = ?');
			$query->execute(array(md5($data['password']),$data['staff_id']));
			//update session
			Login::updateLivePassword(md5($data['password']));
		}
		$query = $this->db->prepare('update staff set username = ?, email = ? where staff_id = ?');
		$query->execute(array($data['username'],$data['email'],$data['staff_id']));
		return $data['staff_id'];
	}

	public function delete($data){
		if(!isset($data['staff_id'])) throw new Exception('staff: no staff id');
		if(!isset($data['confirm_delete'])) throw new Exception('staff: delete no confirmed');
		$query = $this->db->prepare('update staff set is_active = ? where staff_id = ?');
		$query->execute(array(0,$data['staff_id']));
		return $data['staff_id'];
	}
	
}
