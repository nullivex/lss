<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Games {

	public $db;

	const RANDOM_LIFE = 600;

	public static function _get(){
		return new Games();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function gameStatus($type=null){
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
		return array($status,$values);
	}

	public function gameList($type=null,$alpha=true,$limit=null){
		list($status,$values) = $this->gameStatus($type);
		$sql = '
			select
			g.*,(select count(*) from game_tags t where t.game_id = g.game_id) as tags,
			c.name as category
			from games g
			left join categories c on c.category_id = g.category_id
			where '.$status.'
		';
		if($alpha) $sql .= ' order by g.name asc';
		else $sql .= ' order by g.created desc';
		if($limit) $sql .= ' limit '.$limit;
		$query = $this->db->prepare($sql); $query->execute($values);
		return $query->fetchAll();
	}

	public function gameSearch($keywords,$type=null){
		list($status,$values) = $this->gameStatus($type);
		$sql = '
			select
			g.*,(select count(*) from game_tags t where t.game_id = g.game_id) as tags,
			c.name as category
			from games g
			left join categories c on c.category_id = g.category_id
			where '.$status.'
		';
		if(substr($keywords,0,1) == '"' && substr($keywords,strlen($keywords)-1,1) == '"'){
			$sql .= ' and g.name like ? ';
			$values[] = '%'.str_replace('"','',$keywords).'%';
		} elseif(preg_match('/\d+/',trim($keywords))){
			$sql .= ' and g.game_id = ? ';
			$values[] = trim($keywords);
		} else {
			$keywords = preg_replace('/\W+/',' ',$keywords);
			$keywords = preg_replace('/\s+/',' ',$keywords);
			$words = explode(' ',$keywords); $lim = count($words); $i = 1;
			foreach($words as $word){
				$inc = isset($inc) ? ' or ' : ' and (';
				$sql .= $inc.' g.name like ? ';
				$values[] = '%'.$word.'%';
				if($i == $lim){$sql .= ' ) '; break;}
				$i++;
			}
		}
		$query = $this->db->prepare($sql); $query->execute($values);
		return $query->fetchAll();
	}

	public function gamesFeatured($limit=null){
		$is_featured = $is_active = 1; $is_deleted = 0;
		$sql = '
			select g.*,c.name as category from games g
			left join categories c on c.category_id = g.category_id 
			where g.is_featured = ? and g.is_active = ? and g.is_deleted = ?
			order by RAND()
		';
		if($limit) $sql .= ' limit '.$limit;
		$query = $this->db->prepare($sql);
		$query->execute(array($is_featured,$is_active,$is_deleted));
		return $query->fetchAll();
	}

	public function gamesMostPlayed($limit=null){
		$is_active = 1; $is_deleted = 0;
		$sql = 'select * from games where is_active = ?  and is_deleted = ? order by plays desc';
		if($limit) $sql .= ' limit '.$limit;
		$query = $this->db->prepare($sql);
		$query->execute(array($is_active,$is_deleted));
		return $query->fetchAll();
	}

	public function gamesRecentlyPlayed($limit=null){
		$is_active = 1; $is_deleted = 0;
		$sql = 'select * from games where is_active = ?  and is_deleted = ? order by last_played desc';
		if($limit) $sql .= ' limit '.$limit;
		$query = $this->db->prepare($sql);
		$query->execute(array($is_active,$is_deleted));
		return $query->fetchAll();
	}

	public function gamesRecentlyAdded($limit=null){
		$is_active = 1; $is_deleted = 0;
		$sql = 'select * from games where is_active = ?  and is_deleted = ? order by created desc';
		if($limit) $sql .= ' limit '.$limit;
		$query = $this->db->prepare($sql);
		$query->execute(array($is_active,$is_deleted));
		return $query->fetchAll();
	}

	public function gamesRandom($limit=null){
		//clear random entries
		$query = $this->db->prepare('delete from games_random where expires < ?');
		$query->execute(array(time()-self::RANDOM_LIFE));
		//check for existing cache
		$query = $this->db->prepare('select * from games_random where `limit` = ?');
		$query->execute(array($limit));
		$random = $query->fetch();
		if(isset($random['games']) && !empty($random['games'])){
			$games = explode(',',$random['games']);
			$in = null;
			for($i=0;$i<count($games);$i++){
				$inc = isset($inc) ? ',' : '';
				$in .= $inc.'?';
			}
			$query = $this->db->prepare('select * from games where game_id in('.$in.')');
			$query->execute($games);
			return $query->fetchAll();
		} else {
			$is_active = 1; $is_deleted = 0;
			$sql = 'select * from games where is_active = ?  and is_deleted = ? order by RAND()';
			if($limit) $sql .= ' limit '.$limit;
			$query = $this->db->prepare($sql);
			$query->execute(array($is_active,$is_deleted));
			$games = $query->fetchAll();
			$ids = array();
			foreach($games as $game) $ids[] = $game['game_id'];
			//store in random cache
			$query = $this->db->prepare('insert into games_random (expires,`limit`,games)values(?,?,?)');
			$query->execute(array((time()+self::RANDOM_LIFE),$limit,implode(',',$ids)));
			return $games;
		}
	}

	public function createParams(){
		return array(
			'name'			=>	'',
			'desc'			=>	'',
			'inst'			=>	'',
			'author'		=>	'',
			'author_url'	=>	'',
			'width'			=>	'400',
			'height'		=>	'300',
			'size'			=>	'1000',
			'icon'			=>	'/games/icons/',
			'thumb'			=>	'/games/thumbs/',
			'large'			=>	'/games/large/',
			'media'			=>	'/games/media/',
			'media_type'	=>	'',
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
				category_id,name,urlname,shortname,`desc`,inst,width,height,icon,size,
				author,author_url,thumb,large,media,media_type,is_tradable,is_featured,is_active,created
			)values(
				:category_id,:name,:urlname,:shortname,:desc,:inst,:width,:height,:icon,:size,
				:author,:author_url,:thumb,:large,:media,:media_type,:is_tradable,:is_featured,:is_active,:created
			)
		');
		$query->execute(array(
				':category_id'	=>	data('category_id'),
				':name'			=>	data('name'),
				':urlname'		=>	urlname(data('name')),
				':shortname'	=>	shortname(data('name')),
				':desc'			=>	data('desc'),
				':inst'			=>	data('inst'),
				':author'		=>	data('author'),
				':author_url'	=>	data('author_url'),
				':width'		=>	data('width'),
				':height'		=>	data('height'),
				':size'			=>	data('size'),
				':icon'			=>	data('icon'),				
				':thumb'		=>	data('thumb'),
				':large'		=>	data('large'),
				':media'		=>	data('media'),
				':media_type'	=>	data('media_type'),
				':is_tradable'	=>	$is_tradable,
				':is_featured'	=>	$is_featured,
				':is_active'	=>	$is_active,
				':created'		=>	time()
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
				media_type = ?, size = ?, author = ?, author_url = ?,
				is_tradable = ?, is_featured = ?, is_active = ?
			where game_id = ?
		');
		$query->execute(array(
			data('category_id'),data('name'),urlname(data('name')),shortname(data('name')),
			data('desc'),data('inst'),data('width'),data('height'),
			data('icon'),data('thumb'),data('large'),data('media'),data('media_type'),
			data('size'),data('author'),data('author_url'),
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
