<?php

require_once(ROOT.'/tools/lib/def.php');

final class UsrDef extends Def {
	
	protected $container_var = 'usrdef';
	
	static $inst = false;
	
	public static function init($user='root',$lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		self::$inst = new UsrDef($user,$lss_path,$iostate);
	}
	
	public static function _get(){
		if(!is_object(self::$inst)) throw new Exception('UsrDef has not been initialized');
		return self::$inst;
	}
	
	private function __construct($user='root',$lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		$this->filename = self::getDefFile($user,$lss_path);
		$this->iostate = $iostate;
		return $this->read();
	}
	
	public static function getDefFile($user,$lss_path){
		return $lss_path.'/users/'.$user.'.lss';
	}

	protected function dataSanitizer(){
		if(!is_array($this->data))
			$this->data = array();
		if(!isset($this->data['ui']))     $this->data['ui']     = DEFAULT_UI;
		if(!isset($this->data['mirror'])) $this->data['mirror'] = DEFAULT_MIRROR;
		if(!isset($this->data['target'])) $this->data['target'] = DEFAULT_TARGET;
		return $this;
	}
	
}
