<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Seed {

	public $db;

	public static function _get(){
		return new Seed();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public static function assigned($seed_id=false,$staff_id=false){
		$assigned = '';
		foreach(Login::$staff as $staff){
			$params = array();
			$params['value'] = $staff['staff_id'];
			$params['desc'] = $staff['username'];
			$params['checked'] = '';
			if($staff['staff_id'] == $staff_id) $params['checked'] = 'selected="selected"';
			$assigned .= Tpl::_get()->parse('global','select_option',$params,true);
		}
		if($seed_id) $params['name'] = 'seed['.$seed_id.'][staff_id]';
		else $params['name'] = 'staff_id';
		$params['options'] = $assigned;
		return Tpl::_get()->parse('global','select',$params,true);
	}

	public function seedList(){
		$query = $this->db->prepare('select * from seed'); $query->execute();
		return $query->fetchAll();
	}

	public function createParams(){
		return array(
			'staff_id'		=>	'',
			'desc'			=>	'',
			'duration'		=>	1,
			'seed'			=>	'daily'
		);
	}

	public static function validate($data){
		Validate::prime($data);
		Validate::go('staff_id')->not('blank')->type('num');
		Validate::go('desc')->not('blank');
		Validate::go('duration')->not('blank')->type('num');
		Validate::go('seed')->not('blank');
		Validate::paint();
	}

	public function create($data){
		print_r($data);
		data($data);
		self::validate($data);
		$sql = 'insert into seed (staff_id,`desc`,duration,seed)values(?,?,?,?)';
		$query = $this->db->prepare($sql);
		$query->execute(array(data('staff_id'),data('desc'),data('duration'),data('seed')));
		return $this->db->lastInsertId();
	}

	public function update($data){
		print_r($data);
		data($data);
		foreach(data('seed') as $seed_id => $seed){
			data($seed);
			if(!$seed_id) continue;
			self::validate($seed);
			$sql = 'update seed set staff_id = ?, `desc` = ?, duration = ?, seed = ? where seed_id = ?';
			$query = $this->db->prepare($sql);
			$query->execute(array(data('staff_id'),data('desc'),data('duration'),data('seed'),$seed_id));
		}
		return true;
	}

	public function delete($data){
		data($data);
		if(!data('seed_id')) throw new Exception('seed: no seed id');
		$query = $this->db->prepare('delete from seed where seed_id = ?');
		$query->execute(array(data('seed_id')));
		return data('staff_id');
	}

	public function seedTasks($noplay=true){
		if($noplay && (date('D') == 'Sat' || date('D') == 'Sun')) return;
		$sql = 'select * from seed';
		$query = $this->db->prepare($sql); $query->execute();
		foreach($query->fetchAll() as $seed){
			if(!$seed['last']) $this->createTask($seed); continue;
			switch($seed['seed']){
				case 'daily':
					if($seed['last'] < mktime(0,0,0)) $this->createTask($seed); continue;
					break;
				case 'monday':
				case 'tuesday':
				case 'wednesday':
				case 'thursday':
				case 'friday':
					if($seed['last'] < strtotime('this '.$seed['seed'])) $this->createTask($seed); continue;
					break;
				case 'monthly':
					if($seed['last'] < mktime(0,0,0,date('n'),1)) $this->createTask($seed); continue;
					break;
			}
		}
	}

	public function createTask($data){
		data($data);
		$sql = 'insert into tasks (staff_id,`desc`,duration)values(?,?,?)';
		$query = $this->db->prepare($sql);
		$query->execute(array(data('staff_id'),data('desc'),data('duration')));
		$sql = 'update seed set last = ? where seed_id = ?';
		$query = $this->db->prepare($sql);
		$query->execute(array(time(),data('seed_id')));
		return $this->db->lastInsertId();
	}

}
