<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Ads {

	public $db;

	public static function _get(){
		return new Ads();
	}

	public function __construct(){
		$this->db = Db::_get();
	}

	public function adList(){
		$query = $this->db->prepare('select * from ads');
		$query->execute();
		return $query->fetchAll();
	}

	public function get($ad_id){
		$query = $this->db->prepare('select * from ads where ad_id = ? limit 1');
		$query->execute(array($ad_id));
		$ad = $query->fetch(); $query->closeCursor();
		return $ad;
	}

	public function adPick(){
		$query = $this->db->prepare('select * from ads');
		$query->execute();
		$ads = $query->fetchAll();
		$refs = array();
		//pass 1 do grouping
		foreach($ads as $ad){
			if($ad['priority'] > 0){
				//priority ads get better ods
				for($i=0;$i<$ad['priority'];$i++) $refs[$ad['ref']][] = $ad;
			} else $refs[$ad['ref']][] = $ad;
		}
		$ads = array();
		//pass 2 do selection
		foreach($refs as $ad) $ads[] = $ad[mt_rand(0,(count($ad)-1))];
		return $ads;
	}

	public function publish(){
		require_once(ROOT.'/lib/tpl.php');
		$tpl = Tpl::_get();
		$list = $this->adPick();
		foreach($list as $ad){
			$ad['url'] = Url::ad_out($ad['ad_id']);
			$tpl->setConstant('ad_'.$ad['ref'],$this->parse($ad['code'],$ad));
		}
	}

	public function parse($code,$tags=array()){
		require_once(ROOT.'/lib/tpl.php');
		$tags = array_merge(Tpl::_get()->getConstants(),$tags);
		foreach($tags as $tag => $value) $code = str_replace('[['.$tag.']]',$value,$code);
		return $code;
	}

	public function hit($ad_id,$ip){
		//add hit
		$query = $this->db->prepare('update ads set hits = hits + 1 where ad_id = ?');
		$query->execute(array($ad_id));
		if(is_null($ip)) return false; //nothing left to do without an ip
		//add unique hit
		$query = $this->db->prepare('select * from ad_hits where ad_id = ? and ip = ? and hit > ?');
		$query->execute(array($ad_id,$ip,mktime(0,0,0)));
		$hit = $query->fetch(); $query->closeCursor();
		if(!(isset($hit['ad_hit_id']) && !empty($hit['ad_hit_id']))){
			$query = $this->db->prepare('update ads set uhits = uhits + 1 where ad_id = ?');
			$query->execute(array($ad_id));
		}
		$query = $this->db->prepare('insert into ad_hits (ad_id,ip,hit)values(?,?,?)');
		$query->execute(array($ad_id,$ip,time()));
		return true;
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
			'name'			=>	'',
			'ref'			=>	'',
			'code'			=>	'',
			'url'			=>	'',
			'priority'		=>	0
		);
	}

	public function editParams($ad_id){
		$query = $this->db->prepare('select * from ads where ad_id = ?');
		$query->execute(array($ad_id));
		$result = $query->fetch();
		if(empty($result['ad_id'])) throw new Exception("ads: no ad found");
		return $result;
	}

	public static function validate($data){
		Validate::prime($data);
		Validate::go('name')->not('blank');
		Validate::go('ref')->not('blank')->type('alnum');
		Validate::go('code')->not('blank');
		Validate::go('url')->not('blank');
		Validate::go('priority')->not('blank')->type('num');
		Validate::paint();
	}

	public function create($data){
		data($data); self::validate($data);
		$query = $this->db->prepare('insert into ads(name,ref,code,url,priority)values(?,?,?,?,?)');
		$query->execute(array(data('name'),shortname(data('ref')),data('code'),data('url'),data('priority')));
		return $this->db->lastInsertId();
	}

	public function edit($data){
		data($data); self::validate($data);
		if(!data('ad_id')) throw new Exception('ads: ad not found');
		$query = $this->db->prepare('update ads set name = ?, ref = ?, code = ?, url = ?, priority = ? where ad_id = ?');
		$query->execute(array(data('name'),shortname(data('ref')),data('code'),data('url'),data('priority'),data('ad_id')));
		return data('category_id');
	}

	public function delete($data){
		data($data); Validate::prime($data);
		Validate::go('ad_id')->not('blank')->type('num');
		Validate::go('confirm_delete')->not('blank');
		Validate::paint();
		$query = $this->db->prepare('delete from ads where ad_id = ?');
		$query->execute(array(data('ad_id')));
		return data('ad_id');
	}

}
