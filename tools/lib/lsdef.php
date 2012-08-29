<?php

require_once(ROOT.'/tools/lib/def.php');

final class LsDef extends Def {
	
	protected $container_var = 'lsdef';
	
	static $inst = false;
	
	public static function init($lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		self::$inst = new LsDef($lss_path,$iostate);
	}
	
	public static function _get(){
		if(!is_object(self::$inst)) throw new Exception('LsDef has not been initialized');
		return self::$inst;
	}
	
	private function __construct($lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		$this->filename = self::getDefFile($lss_path);
		$this->iostate = $iostate;
		return $this->read();
	}
	
	public static function getDefFile($lss_path){
		return $lss_path.'/users/_sys_.lss';
	}

	protected function dataSanitizer(){
		//main array
		if(!is_array($this->data)) $this->data = array();
		//default values
		if(!isset($this->data['ui'])) $this->data['ui'] = DEFAULT_UI;
		if(!isset($this->data['target'])) $this->data['target'] = DEFAULT_TARGET;
		//default arrays
		if(!isset($this->data['public_mirror'])) $this->data['public_mirror'] = array();
		if(!isset($this->data['private_mirror'])) $this->data['private_mirror'] = array();
		if(!isset($this->data['personal_mirror'])) $this->data['personal_mirror'] = array();
		//chainable
		return $this;
	}
	
}
