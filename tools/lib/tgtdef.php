<?php

require_once(ROOT.'/tools/lib/def.php');

final class TgtDef extends Def {
	
	protected $container_var = 'tgtdef';
	
	public function __construct($tgt,$iostate=self::READONLY){
		$this->filename = self::getDefFile($tgt);
		$this->iostate = $iostate;
		return $this->read();
	}
	
	public static function getDefFile($tgt){
		return $tgt.'/.pkgs';
	}

	protected function dataSanitizer(){
		if(!is_array($this->data)) $this->data = array();
		if(!isset($this->data['installed'])) $this->data['installed'] = array();
		return $this;
	}
	
}
