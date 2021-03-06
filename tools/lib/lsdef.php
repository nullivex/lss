<?php

require_once(ROOT.'/tools/lib/def.php');

final class LsDef extends Def {

	protected $container_var = 'lsdef';

	static $inst = false;

	public static function init($lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		self::$inst = new LsDef($lss_path,$iostate);
	}

	public static function _get(){
		if(!is_object(self::$inst)) throw new Exception('LsDef has not been initialized',ERR_NOT_INITIALIZED);
		return self::$inst;
	}

	private function __construct($lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		$this->filename = self::getDefFile($lss_path);
		$this->iostate = $iostate;
		$this->read();
	}

	public static function getDefFile($lss_path,$absolute=false){
		if($absolute) return ROOT.'/'.$lss_path.'/.lss';
		return $lss_path.'/.lss';
	}

	protected function dataSanitizer(){
		//main array
		if(!is_array($this->data)) $this->data = array();
		//default values
		if(!isset($this->data['ui'])) $this->data['ui'] = DEFAULT_UI;
		if(!isset($this->data['target'])) $this->data['target'] = DEFAULT_TARGET;
		if(!isset($this->data['defparser'])) $this->data['defparser'] = DEFAULT_DEFPARSER;
		//default arrays
		if(!isset($this->data['mirror'])) $this->data['mirror'] = array();
		//chainable
		return $this;
	}

}
