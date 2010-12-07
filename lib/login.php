<?php
/*
 * Tacker - Simple task tracker system
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

class Login {

	static $staff = array();
	static $users = array();
	static $user = false;
	static $root_user = false;
	static $manage = false;
	
	public $referskip = array('login=true');

	public static function _get($root_user){
		return new Login($root_user);
	}

	public function __construct($root_user){
		self::$root_user = $root_user;
		self::setStaff(self::staffList());
		session('login_referrer',server('HTTP_REFERER'));
	}

	public static function setStaff($staff){
		self::$staff = $staff;
		foreach(self::$staff as $staff) self::$users[$staff['username']] = $staff['password'];
	}
	
	public static function staffList(){
		$query = Db::_get()->prepare('select * from staff where is_active = ?');
		$query->execute(array(1));
		return $query->fetchAll();
	}

	public static function staffId(){
		if(!self::$user) throw new Exception('staff not populated');
		return self::$user['staff_id'];
	}

	public static function manage(){
		if(session('manage') == 'true') return true;
		return false;
	}

	public static function requireManager(){
		if(!isset(self::$user['is_manager'])) throw new Exception('staff not populated');
		if(self::$user['is_manager'] == 0) throw new Exception('permission denied <a href="'.Url::tasks().'">return</a>');
	}

	public static function updateLivePassword($pw){
		session('login_pass',$pw);
	}

	public function check(){
		if(post('login') && get('login')) return $this->process();
		if(get('logout')) return $this->logout();
		if(!isset($_SESSION['login'])){
			return $this->loginPage();
		} else {
			try {
				$username = $this->verify();
				$this->setup($username);
				if(get('manage')) $this->toggleManage();
			} catch (Exception $e){
				loginError($e->getMessage());
			}
		}
	}

	protected function verify(){
		if(!session('login_user')) throw new Exception("login: user not found");
		if(!session('login_pass')) throw new Exception("login: pass not found");
		if(!in_array(session('login_user'),array_keys(self::$users))) throw new Exception('login: user doesnt exist');
		if(!$this->pass(session('login_pass'),self::$users[session('login_user')],true)) throw new Exception("login: password does not match");
		return session('login_user');
	}

	protected function setup($username){
		foreach(self::$staff as $staff){
			if($staff['username'] == $username){
				self::$user = $staff;
				break;
			}
		}
		if(!session('manage')) session('manage','false');
	}

	protected function toggleManage(){
		if(session('manage') == 'true') session('manage','false');
		else if(session('manage') == 'false' && self::$user['is_manager']) session('manage','true');
		else return session('manage','false');
		redirect(server('HTTP_REFERER'));
	}

	public function loginPage(){
		Tpl::_get()->parse('login','page');
		output(Tpl::_get()->output());
	}

	protected function process(){
		if(!post('login')) $this->loginPage();
		try {
			$this->auth(post('username'),post('password'));
			$this->start(post('username'),post('password'));
			redirect($this->referrerVerify(session('login_referer')),true);
		} catch(Exception $e){
			alert($e->getMessage(),false);
			$this->loginPage();
		}
	}

	public function auth($user,$pass){
		if(!in_array($user,array_keys(self::$users))) throw new Exception('login: user not found');
		if(!$this->pass($pass,self::$users[$user])) throw new Exception('login: pass didnt match');
	}

	protected function pass($crim,$police,$md5=false){
		if(strlen($police) == 32) $md5=true;
		if($md5 && strlen($crim) != 32){
			$crim = md5($crim);
		}
		if($md5 && strlen($police) != 32){
			$police = md5($police);
		}
		if($crim != $police) return false;
		return true;
	}

	protected function start($user,$pass){
		session('login',true);
		session('login_user',$user);
		session('login_pass',md5($pass));
	}

	protected function end(){
		session_delete('login','login_user','login_pass','manage');
	}

	protected function logout(){
		$this->end();
		redirect(Url::home(),true);
	}

	protected function referrerVerify($url){
		if(!is_array($this->referskip)) return $url;
		foreach($this->referskip as $part){
			if(preg_match('/'.preg_quote($part).'/i',$url)) return null;
		}
		return $url;
	}

}
