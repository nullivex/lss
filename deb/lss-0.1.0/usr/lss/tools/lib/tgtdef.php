<?php

require_once(ROOT.'/tools/lib/def.php');

final class TgtDef extends Def {

	protected $container_var = 'tgtdef';

	static $inst = false;

	public static function init($tgt,$iostate=self::READONLY){
		self::$inst = new TgtDef($tgt,$iostate);
	}

	public static function _get(){
		if(!is_object(self::$inst)) throw new Exception('TgtDef has not been initialized');
		return self::$inst;
	}

	private function __construct($tgt,$iostate=self::READONLY){
		$this->filename = self::getDefFile($tgt);
		$this->iostate = $iostate;
		return $this->read();
	}

	public static function getDefFile($tgt){
		return $tgt.'/.lss';
	}

	protected function dataSanitizer(){
		if(!is_array($this->data)) $this->data = array();
		//default values
		if(!isset($this->data['ui']))     $this->data['ui']     = UsrDef::_get()->get('ui');
		//default arrays
		if(!isset($this->data['installed'])) $this->data['installed'] = array();
		if(!isset($this->data['mirror'])) $this->data['mirror'] = array();
		if(!isset($this->data['mirrorauth'])) $this->data['mirrorauth'] = array();
		return $this;
	}

}
