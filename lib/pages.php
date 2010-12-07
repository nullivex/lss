<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Pages {

	public $db;

	public static function _get(){
		return new Pages();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function pageList(){
		$query = $this->db->prepare('select * from pages');
		$query->execute();
		return $query->fetchAll();
	}

	public function createParams(){
		return array(
			'name'			=>	'',
			'content'		=>	''
		);
	}

	public function editParams($page_id){
		$query = $this->db->prepare('select * from pages where page_id = ?');
		$query->execute(array($page_id));
		$result = $query->fetch();
		if(empty($result['page_id'])) throw new Exception("pages: no page found");
		return $result;
	}

	public function view($page){
		$query = $this->db->prepare('select * from pages where urlname = ?');
		$query->execute(array($page));
		$result = $query->fetch();
		if(empty($result['page_id'])) throw new Exception("pages: no page found");
		return $result;
	}

	public static function validate($data){
		Validate::prime($data);
		Validate::go('name')->not('blank');
		Validate::paint();
	}

	public function create($data){
		data($data); self::validate($data);
		$query = $this->db->prepare('insert into pages(name,urlname,content)values(?,?,?)');
		$query->execute(array(data('name'),urlname(data('name')),html_entity_decode(data('content'))));
		return $this->db->lastInsertId();
	}

	public function edit($data){
		data($data); self::validate($data);
		if(!data('page_id')) throw new Exception('pages: page not found');
		$query = $this->db->prepare('update pages set name = ?, urlname = ?, content = ? where page_id = ?');
		$query->execute(array(data('name'),urlname(data('name')),html_entity_decode(data('content')),data('page_id')));
		return data('page_id');
	}

	public function delete($data){
		data($data); Validate::prime($data);
		Validate::go('page_id')->not('blank')->type('num');
		Validate::go('confirm_delete')->not('blank');
		Validate::paint();
		$query = $this->db->prepare('delete from pages where page_id = ?');
		$query->execute(array(data('page_id')));
		return data('page_id');
	}

}
