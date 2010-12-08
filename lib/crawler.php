<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Crawler {

	public $db;

	const GAME_REL = 'enclosure';
	const GAME_DESC = 'description';
	const GAME_INST = 'instructions';
	const GAME_KEY = 'keymappings';
	const GAME_CTL = 'controlscheme';
	const GAME_KEYS = 'keywords';
	const GAME_EMED = 'embed';
	const GAME_RES = 'resolution';
	const GAME_URL = 'slug';
	const GAME_SIZE = 'swffilesize';
	const FILE_LIMIT = 1000;

	public static function _get(){
		return new Crawler();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function gameList($pub,$url,&$seed,$chunk,$advance){
		$url = str_replace('[[pub]]',$pub,$url);
		$games = simplexml_load_string(file_get_contents($url.'?limit='.$chunk.'&offset='.$seed.'&tag=-zh-cn'));
		if(count($games->entry) == 0) return false;
		$seed += $advance;
		return $games->entry;
	}

	public function category($game){
		$category_id = null;
		$category = each($game->category);
		$category = isset($category['value']['term']) ? $category['value']['term'] : null;
		$query = $this->db->prepare('select * from categories where shortname = ?');
		$query->execute(array(shortname($category)));
		$result = $query->fetch(); $query->closeCursor();
		if(isset($result['category_id']) && !empty($result['category_id'])) return $result['category_id'];
		return Categories::_get()->create(array('name'=>$category));
	}

	public function gameData($game,$category_id){
		$data = array();
		$data['is_active'] = '1';
		$data['is_tradable'] = '1';
		$data['category_id'] = $category_id;
		$data['name'] = (string)$game->title;
		$data['author'] = (string)$game->author->name;
		$data['author_url'] = (string)$game->author->uri;
		$data['icon'] = (string)$game->summary->div->a->img['src'];
		foreach($game->link as $link){
			if((string)$link['rel'] == self::GAME_REL){
				$data['media'] = (string)$link['href'];
				$data['media_type'] = (string)$link['type'];
			}
		}
		
		$i = 0; $summary = array();
		foreach($game->summary->div->dl->dt as $value){
			$summary[shortname($value[0])] = (string)$game->summary->div->dl->dd[$i][0]; $i++;
		}
		foreach($summary as $type => $val){
			switch(shortname($type)){
				case self::GAME_DESC:
					$data['desc'] = $val;
					break;
				case self::GAME_INST:
					$data['inst'] = $val;
					break;
				case self::GAME_KEYS:
					$data['keywords'] = $val;
					break;
				case self::GAME_RES:
					$tmp = explode('x',strtolower($val));
					$data['width'] = isset($tmp[0]) ? $tmp[0] : 500;
					$data['height'] = isset($tmp[1]) ? $tmp[1] : 300;
					break;
				case self::GAME_SIZE:
					$data['size'] = $val;
					break;
				default:
					//pass
					break;
			}
		}
		return $data;
	}

	public function checkGame($shortname){
		$query = $this->db->prepare('select * from games where shortname = ?');
		$query->execute(array($shortname));
		$result = $query->fetch(); $query->closeCursor();
		if(isset($result['game_id']) && !empty($result['game_id'])) return true;
		return false;
	}

	public static function ext($path){
		$path = explode('.',$path);
		return end($path);
	}

	public static function nextFile($shortname,$type,$ext=''){
		$base = ROOT.'/games/'.$type;
		$folders = scandir($base); rsort($folders);
		$dir = current($folders);
		if($dir == '.' || $dir == '..'){
			$dir = 'f1';
			if(!file_exists($base.'/'.$dir)) mkdir($base.'/'.$dir);
		}
		if(count(scandir($base.'/'.$dir)) > self::FILE_LIMIT){
			$num = intval(preg_replace('/\D+/','',$dir)); $num++;
			$dir = 'f'.$num;
			if(!file_exists($base.'/'.$dir)) mkdir($base.'/'.$dir);
		}
		if($ext) $ext = '.'.$ext;
		return '/games/'.$type.'/'.$dir.'/'.$shortname.$ext;
	}

	public function downloadGame($url,$shortname){
		$file = self::nextFile($shortname,'media',self::ext($url));
		$handle = fopen(ROOT.'/'.$file,'w');
		fwrite($handle,file_get_contents($url));
		fclose($handle);
		return $file;
	}

	public function downloadThumb($url,$shortname){
		$file = self::nextFile($shortname,'thumbs',self::ext($url));
		$handle = fopen(ROOT.'/'.$file,'w');
		fwrite($handle,file_get_contents($url));
		fclose($handle);
		return $file;
	}

}
