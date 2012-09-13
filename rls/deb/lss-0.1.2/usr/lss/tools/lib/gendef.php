<?php

require_once(ROOT.'/tools/lib/def.php');

final class GenDef extends Def {

	protected $container_var = 'gendef';

	public function __construct($file,$iostate=self::READONLY){
		$this->filename = $file;
		$this->iostate = $iostate;
		$this->read();
	}

	protected function dataSanitizer(){
		if(!is_array($this->data)) $this->data = array();
		return $this;
	}

}
