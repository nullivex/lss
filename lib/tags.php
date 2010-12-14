<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Tags {

	public $db;

	const RANDOM_LIFE = 600;

	public static function _get(){
		return new Tags();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function tagList(){
		$query = $this->db->prepare('select * from tags');
		$query->execute();
		return $query->fetchAll();
	}

	public function randomCloud($limit=10){
		$tags = $this->random($limit);
		$tags = $this->scale($tags);
		return $tags;
	}

	public function random($limit=10){
		//clear expires
		$query = $this->db->prepare('delete from tags_random where expires < ?');
		$query->execute(array(time()-self::RANDOM_LIFE));
		//check for cache
		$query = $this->db->prepare('select * from tags_random where `limit` = ?');
		$query->execute(array($limit));
		$random = $query->fetch(); $query->closeCursor();
		if(!(isset($random['tag_random_id']) && !empty($random['tag_random_id']))){
			//get a real tag list
			$query = $this->db->prepare('select * from tags order by rand() limit '.$limit);
			$query->execute();
			$tags = $query->fetchAll();
			$ids = array();
			foreach($tags as $tag) $ids[] = $tag['tag_id'];
			$query = $this->db->prepare('insert into tags_random (expires,`limit`,tags)values(?,?,?)');
			$query->execute(array(time()+self::RANDOM_LIFE,$limit,implode(',',$ids)));
			return $tags;
		} else {
			$ids = explode(',',$random['tags']);
			$in = '';
			for($i=0;$i<count($ids);$i++){
				$inc = isset($inc) ? ',' : '';
				$in .= $inc.'?';
			}
			$query = $this->db->prepare('select * from tags where tag_id in('.$in.')');
			$query->execute($ids);
			return $query->fetchAll();
		}
	}

	public function scale($tags){
		//pass 1 - get min and max
		$min = $max = 0;
		foreach($tags as $tag){
			if($min == 0 || $tag['size'] < $min) $min = $tag['size'];
			if($max == 0 || $tag['size'] > $max) $max = $tag['size'];
		}
		//figure out limits
		$max_size = Config::get('tags','size_max');
		$min_size = Config::get('tags','size_min');
		//figure factor
		$factor_max = $max / $max_size;
		$factor_min = $min / $min_size;
		//pass 2 - adjust size
		foreach($tags as &$tag){
			if($tag['size'] > $max_size){
				$tag['size'] = $tag['size'] / $factor_max; continue;
			}
			while($tag['size'] < $min_size) $tag['size'] = $tag['size'] / $factor_min;
			if($tag['size'] > $max_size) $tag['size'] = $tag['size'] / $factor_max;
		}
		return $tags;
	}

	public function updateSize($tag_id,$count){
		$query = $this->db->prepare('update tags set size = ? where tag_id = ?');
		$query->execute(array($count,$tag_id));
		return $tag_id;
	}

}
