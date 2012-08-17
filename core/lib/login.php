<?php
/*
 * LSS Core
 * OpenLSS - Light, sturdy, stupid simple
 * 2010 Nullivex LLC, All Rights Reserved.
 * Bryan Tong <contact@nullivex.com>
 *
 *   OpenLSS is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   OpenLSS is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenLSS.  If not, see <http://www.gnu.org/licenses/>.
 */

class Login {

	static $members = array();
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
		self::setMembers(self::memberList());
		session('login_referrer',server('HTTP_REFERER'));
	}

	public static function setMembers($members){
		self::$members = $members;
		foreach(self::$members as $member) self::$users[$member['username']] = $member['password'];
	}

	public static function memberList(){
		$query = Db::_get()->prepare('select * from members where is_active = ?');
		$query->execute(array(1));
		return $query->fetchAll();
	}

	public static function memberId(){
		if(!self::$user) throw new Exception('member not populated');
		return self::$user['member_id'];
	}

	public static function loggedIn(){
		if(!self::$user) return false;
		return true;
	}

	public static function requireLogin(){
		if(!self::$user) throw new Exception('must be logged in');
		return true;
	}

	public static function requireAffiliate(){
		if(!self::$user['is_affiliate']) throw new Exception('permission denied');
		return true;
	}

	public static function updateLivePassword($pw){
		session('member_pass',$pw);
	}

	public function check(){
		if(post('login')) return $this->process();
		if(get('logout') || get('do') == 'logout') return $this->logout();
		if(!session('member')){
			//guest
			self::$user = false;
			return $this;
		} else {
			try {
				$username = $this->verify();
				$this->setup($username);
			} catch (Exception $e){
				loginError($e->getMessage());
			}
		}
		return $this;
	}

	protected function verify(){
		if(!session('member_user')) throw new Exception("login: user not found");
		if(!session('member_pass')) throw new Exception("login: pass not found");
		if(!in_array(session('member_user'),array_keys(self::$users))) throw new Exception('login: user doesnt exist');
		if(!$this->pass(session('member_pass'),self::$users[session('member_user')],true)) throw new Exception("login: password does not match");
		return session('member_user');
	}

	protected function setup($username){
		foreach(self::$members as $member){
			if($member['username'] == $username){
				self::$user = $member;
				break;
			}
		}
	}

	protected function process(){
		if(!post('login')) $this->loginPage();
		try {
			$this->auth(post('username'),post('password'));
			$this->start(post('username'),post('password'));
			redirect($this->referrerVerify(session('login_referer')),true);
		} catch(Exception $e){
			alert($e->getMessage(),false);
		}
		return $this;
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
		session('member',true);
		session('member_user',$user);
		session('member_pass',md5($pass));
	}

	protected function end(){
		session_delete('member','member_user','member_pass');
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

	public function memberBar(){
		if(self::loggedIn() && self::$user['is_affiliate'] == 1) $tpl = 'member_bar_affiliate';
		elseif(self::loggedIn()) $tpl = 'member_bar';
		else $tpl = 'login_bar';
		Tpl::_get()->setConstant('member_bar',Tpl::_get()->parse('header',$tpl,array(),true));
	}

}
