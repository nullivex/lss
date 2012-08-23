<?php

require_once(ROOT.'/tools/lib/def.php');

final class UsrDef extends Def {
	
	public function __construct($user='root',$lss_path=DEFAULT_LSS,$iostate=self::READONLY){
		$this->filename = self::getDefFile($user,$lss_path);
		$this->iostate = !is_bool($iostate) ? self::READONLY : $iostate;
		return $this->read();
	}
	
	public static function getDefFile($user,$lss_path){
		return $lss_path.'/users/'.$user.'.lss';
	}

	protected function dataSanitizer(){
		if(!is_array($this->data))
			$this->data = array();
		if(!isset($this->data['default_mirror'])) $this->data['default_mirror'] = DEFAULT_MIRROR;
		if(!isset($this->data['default_target'])) $this->data['default_target'] = DEFAULT_TARGET;
		return $this;
	}
	
}
