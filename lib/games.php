<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Games {

	public $db;

	public static function _get(){
		return new Games();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function gameList($type=null,$limit=null){
		$is_featured = 0; $is_tradable = 0; $is_active = 1; $is_deleted = 0;
		$values = array();
		switch($type){
			case 'featured':
				$status = 'g.is_featured = ? and g.is_active = ? and g.is_deleted = ?';
				$values[] = 1; $values[] = $is_active; $values[] = $is_deleted;
				break;
			case 'tradable':
				$status = 'g.is_tradable = ? and g.is_active = ? and g.is_deleted = ?';
				$values[] = 1; $values[] = $is_active; $values[] = $is_deleted;
				break;
			case 'intradable':
				$status = 'g.is_tradable = ? and g.is_active = ? and g.is_deleted = ?';
				$values[] = $is_tradable; $values[] = $is_active; $values[] = $is_deleted;
				break;
			case 'inactive':
				$status = 'g.is_active = ? and g.is_deleted = ?';
				$values[] = 0; $values[] = $is_deleted;
				break;
			case 'deleted':
				$status = 'g.is_deleted = ?';
				$values[] = 1;
				break;
			default:
				$status = 'g.is_active = ? and is_deleted = ?';
				$values[] = $is_active; $values[] = $is_deleted;
				break;
		}
		$sql = '
			select
			g.*,(select count(*) from game_tags t where t.game_id = g.game_id) as tags,
			c.name as category
			from games g
			left join categories c on c.category_id = g.category_id
			where '.$status.'
		';
		if($limit) $sql .= ' limit '.$limit;
		$query = $this->db->prepare($sql); $query->execute($values);
		return $query->fetchAll();
	}

	public function createParams(){
		return array(
			'name'			=>	'',
			'desc'			=>	'',
			'inst'			=>	'',
			'width'			=>	'400',
			'height'		=>	'300',
			'icon'			=>	'/games/icons/',
			'thumb'			=>	'/games/thumbs/',
			'large'			=>	'/games/large/',
			'media'			=>	'/games/media/',
			'is_tradable'	=>	'1',
			'is_featured'	=>	'',
			'is_active'		=>	'1'
		);
	}

	public function editParams($game_id){
		$query = $this->db->prepare('select * from games where game_id = ?');
		$query->execute(array($game_id));
		$result = $query->fetch();
		if(empty($result['game_id'])) throw new Exception("games: no game found");
		return $result;
	}

	public static function validate($data){
		Validate::prime($data);
		Validate::go('name')->not('blank');
		Validate::go('width')->not('blank')->type('num');
		Validate::go('height')->not('blank')->type('num');
		Validate::paint();
	}

	public function create($data){
		data($data); self::validate($data);
		$is_tradable = data('is_tradable') ? 1 : 0;
		$is_featured = data('is_featured') ? 1 : 0;
		$is_active = data('is_active') ? 1 : 0;
		$query = $this->db->prepare('
			insert into games
			(
				category_id,name,urlname,shortname,`desc`,inst,width,height,icon,
				thumb,large,media,is_tradable,is_featured,is_active,created
			)values(
				?,?,?,?,?,?,?,?,?,
				?,?,?,?,?,?,?
			)
		');
		$query->execute(array(
				data('category_id'),data('name'),urlname(data('name')),shortname(data('name')),
				data('desc'),data('inst'),data('width'),data('height'),data('icon'),
				data('thumb'),data('large'),data('media'),
				$is_tradable,$is_featured,$is_active,time()
		));
		return $this->db->lastInsertId();
	}

	public function edit($data){
		data($data); self::validate($data);
		if(!data('game_id')) throw new Exception('games: game not found');
		$is_tradable = data('is_tradable') ? 1 : 0;
		$is_featured = data('is_featured') ? 1 : 0;
		$is_active = data('is_active') ? 1 : 0;
		$query = $this->db->prepare('
			update games set
				category_id = ?, name = ?, urlname = ?, shortname = ?,
				`desc` = ?, inst = ?, width = ?, height = ?,
				icon = ?, thumb = ?, large = ?, media = ?,
				is_tradable = ?, is_featured = ?, is_active = ?
			where game_id = ?
		');
		$query->execute(array(
			data('category_id'),data('name'),urlname(data('name')),shortname(data('name')),
			data('desc'),data('inst'),data('width'),data('height'),
			data('icon'),data('thumb'),data('large'),data('media'),
			$is_tradable,$is_featured,$is_active,data('game_id')
		));
		return data('game_id');
	}

	public function delete($data){
		data($data); Validate::prime($data);
		Validate::go('game_id')->not('blank')->type('num');
		Validate::go('confirm_delete')->not('blank');
		Validate::paint();
		$params = $this->editParams(data('game_id'));
		$deleted = $params['is_deleted'] ? 0 : 1;
		$query = $this->db->prepare('update games set is_deleted = ? where game_id = ?');
		$query->execute(array($deleted,data('game_id')));
		return data('game_id');
	}

}
