<?php

require_once(ROOT.'/tools/lib/def.php');

final class UsrDef extends Def {
	
	protected $container_var = 'usrdef';
	
	static $inst = false;
	
	public static function init($homedir=null,$iostate=self::READONLY){
		self::$inst = new UsrDef($homedir,$iostate);
	}
	
	public static function _get(){
		if(!is_object(self::$inst)) throw new Exception('UsrDef has not been initialized');
		return self::$inst;
	}
	
	private function __construct($homedir=null,$iostate=self::READONLY){
		$this->filename = self::getDefFile($homedir);
		$this->iostate = $iostate;
		return $this->read();
	}
	
	public static function getDefFile($homedir=null){
		if(is_null($homedir)) return ROOT.'/.usr.lss';
		return $homedir.'/.lss';
	}

	protected function dataSanitizer(){
		if(!is_array($this->data)) $this->data = array();
		//default values
		if(!isset($this->data['ui']))     $this->data['ui']     = LsDef::_get()->get('ui');
		if(!isset($this->data['target'])) $this->data['target'] = LsDef::_get()->get('target');
		//default arrays
		if(!isset($this->data['mirror'])) $this->data['mirror'] = array();
		return $this;
	}
	
}
