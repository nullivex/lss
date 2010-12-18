<?php
/*
 * Sloppygames - Arcade Gaming
 * OpenLSS - Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Categories {

	public $db;

	public static function _get(){
		return new Categories();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public static function nav($file,$tpl){
		$list = Categories::_get()->categoryList();
		$cats = '';
		foreach($list as $category){
			$params = $category;
			$params['url'] = Url::game_list_category($category['category_id']);
			$cats .= Tpl::_get()->parse($file,$tpl,$params,true);
		}
		return $cats;
	}

	public function categoryList(){
		$query = $this->db->prepare('
			select 
				c.*,(select count(*) from games g where g.category_id = c.category_id) as games,
				(
					select thumb from games h where h.category_id = c.category_id
					order by h.plays desc limit 1
				) as thumb
			from categories c
			order by c.name asc
		');
		$query->execute();
		return $query->fetchAll();
	}

	public function get($category_id){
		$query = $this->db->prepare('select * from categories where category_id = ?');
		$query->execute(array($category_id));
		$result = $query->fetch(); $query->closeCursor();
		if(empty($result['category_id'])) throw new Exception("categories: no categories found");
		return $result;
	}

	public function categoryDrop($name='category_id',$category_id=null){
		$assigned = '';
		foreach($this->categoryList() as $category){
			$params = array();
			$params['value'] = $category['category_id'];
			$params['desc'] = $category['name'];
			$params['checked'] = '';
			if($category['category_id'] == $category_id) $params['checked'] = 'selected="selected"';
			$assigned .= Tpl::_get()->parse('global','select_option',$params,true);
		}
		$params['name'] = $name;
		$params['options'] = $assigned;
		return Tpl::_get()->parse('global','select',$params,true);
	}

	public function createParams(){
		return array(
			'name'			=>	''
		);
	}

	public function editParams($category_id){
		$query = $this->db->prepare('select * from categories where category_id = ?');
		$query->execute(array($category_id));
		$result = $query->fetch(); $query->closeCursor();
		if(empty($result['category_id'])) throw new Exception("categories: no categories found");
		return $result;
	}

	public static function validate($data){
		Validate::prime($data);
		Validate::go('name')->not('blank');
		Validate::paint();
	}

	public function create($data){
		data($data); self::validate($data);
		$query = $this->db->prepare('insert into categories(name,urlname,shortname)values(?,?,?)');
		$query->execute(array(data('name'),urlname(data('name')),shortname(data('name'))));
		return $this->db->lastInsertId();
	}

	public function edit($data){
		data($data); self::validate($data);
		if(!data('category_id')) throw new Exception('categories: category not found');
		$query = $this->db->prepare('update categories set name = ?, urlname = ?, shortname = ? where category_id = ?');
		$query->execute(array(data('name'),urlname(data('name')),shortname(data('name')),data('category_id')));
		return data('category_id');
	}

	public function delete($data){
		data($data); Validate::prime($data);
		Validate::go('category_id')->not('blank')->type('num');
		Validate::go('new_category_id')->not('blank')->type('num');
		Validate::go('confirm_delete')->not('blank');
		Validate::paint();
		if(data('category_id') == data('new_category_id')) throw new Exception('categories: new category must be diff');
		$query = $this->db->prepare('update games set category_id = ? where category_id = ?');
		$query->execute(array(data('new_category_id'),data('category_id')));
		$query = $this->db->prepare('delete from categories where category_id = ?');
		$query->execute(array(data('category_id')));
		return data('category_id');
	}

}
