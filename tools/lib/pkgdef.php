<?php

require_once(ROOT.'/tools/lib/def.php');

final class PkgDef extends Def {

	protected $container_var = 'pkgdef';

	//meta info
	private $fqn 	 = null;
	private $sqn	 = null;
	private $repo	 = null;
	private $class	 = null;
	private $name	 = null;

	public function __construct($fqn,$iostate=self::READONLY,$readpkg=true){
		//deal with iostate
		$this->iostate = $iostate;
		$this->filename = self::getDefFile($fqn,true);
		//set some meta info for quicker reference
		$this->fqn   = $fqn;
		$this->sqn   = $this->getSQN();
		$this->repo  = $this->getRepo();
		$this->name  = $this->getName();
		$this->class = $this->getClass();
		if($readpkg) $this->read();
	}

	public static function getDefFile($fqn,$absolute=false){
		if($absolute) return ROOT.'/'.DEF_PATH.'/'.$fqn.'/.lss';
		return DEF_PATH.'/'.$fqn.'/.lss';
	}

	public static function createPkg($fqn){
		$def_file = self::getDefFile($fqn,true);
		@mkdir(dirname($def_file),0755,true);
		@touch($def_file);
		return true;
	}

	public static function deletePkg($fqn){
		$def_file = self::getDeffile($fqn,true);
		@unlink($def_file);
		return true;
	}

	public function getFQN(){
		return $this->fqn;
	}

	public function getSQN(){
		if(!is_null($this->sqn)) return $this->sqn;
		list(,$class,$name) = explode('/',$this->fqn);
		return $class.'/'.$name;
	}

	public function getRepo(){
		if(!is_null($this->repo)) return $this->repo;
		list($repo) = explode('/',$this->fqn);
		return $repo;
	}

	public function getName(){
		if(!is_null($this->name)) return $this->name;
		list(,,$name) = explode('/',$this->fqn);
		return $name;
	}

	public function getClass(){
		if(!is_null($this->class)) return $this->class;
		list(,$class) = explode('/',$this->fqn);
		return $class;
	}

	public function getVersion(){
		return mda_get($this->data,'info','version');
	}

	protected function dataSanitizer(){
		if(!is_array($this->data))
			$this->data = array();
		// ensure that 'manifest' is an array, create it if necessary
		if(!isset($this->data['manifest']))
			$this->data['manifest'] = array();
		else if(!is_array($this->data['manifest']))
			$this->data['manifest'] = array($this->data['manifest']);
		// ensure there is a dep array
		if(!isset($this->data['dep']))
			$this->data['dep'] = array();
		else if(!is_array($this->data['dep']))
			$this->data['dep'] = array($this->data['dep']);
		//deal with uniques in the manifest
		remove_dups($this->data['manifest']);
		// $tmp = array_unique($this->data['manifest'],SORT_STRING);
		// sort($tmp,SORT_STRING);
		// $this->data['manifest'] = array_merge($tmp);
		// unset($tmp);
		//setup defaults for package info
		if(!isset($this->data['info'])){
			$this->data['info'] = array();
			$this->data['info']['fqn']		= $this->getFQN();
			$this->data['info']['sqn']		= $this->getSQN();
			$this->data['info']['repo']		= $this->getRepo();
			$this->data['info']['class']	= $this->getClass();
			$this->data['info']['name']		= $this->getName();
		}
		if(!mda_get($this->data,'info','version'))
			$this->data['info']['version'] = DEFAULT_VERSION;
		return $this;
	}

}
