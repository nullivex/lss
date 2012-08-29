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

	function __construct($pkg,$repo=REPO_MAIN,$iostate=self::READONLY,$readpkg=true){
		//deal with iostate
		$this->iostate = $iostate;
		$this->filename = self::getDefFile($repo,$pkg);
		//set some meta info for quicker reference
		$this->fqn   = $this->getFQN();
		$this->sqn   = $this->getSQN();
		$this->repo  = $this->getRepo();
		$this->name  = $this->getName();
		$this->class = $this->getClass();
		if($readpkg) $this->read();
	}

	public static function getDefFile($repo,$pkg){
		return DEF_PATH.'/'.$repo.'/'.$pkg.'.lss';
	}

	public static function createPkg($repo,$pkg){
		$def_file = ROOT.'/'.self::getDefFile($repo,$pkg);
		@mkdir(dirname($def_file),0755,true);
		@touch($def_file);
		return true;
	}

	public static function deletePkg($repo,$pkg){
		$def_file = ROOT.'/'.self::getDeffile($repo,$pkg);
		@unlink($def_file);
		return true;
	}

	public function getFQN(){
		if(!is_null($this->fqn)) return $this->fqn;
		$start = strlen(DEF_PATH)+1; //include the trailing slash
		$len = strlen($this->filename) - $start - 4;
		return substr($this->filename,$start,$len);
	}

	public function getSQN(){
		if(!is_null($this->sqn)) return $this->sqn;
		$start = strlen($this->getRepo()) + 1;
		$len = strlen($this->getFQN()) - $start;
		return substr($this->getFQN(),$start,$len);
	}

	public function getRepo(){
		if(!is_null($this->repo)) return $this->repo;
		$len = strpos($this->getFQN(),'/');
		return substr($this->getFQN(),0,$len);
	}

	public function getName(){
		if(!is_null($this->name)) return $this->name;
		$start = strrpos($this->getFQN(),'/') + 1;
		$len = strlen($this->getFQN()) - $start;
		return substr($this->getFQN(),$start,$len);
	}

	public function getClass(){
		if(!is_null($this->class)) return $this->class;
		$start = strlen($this->getRepo()) + 1;
		$len = strlen($this->getFQN()) - $start - strlen($this->getName()) - 1;
		return substr($this->getFQN(),$start,$len);
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
		if(!isset($this->data['info'])) $this->data['info'] = array();
		$this->data['info']['fqn'    ] = $this->getFQN();
		$this->data['info']['sqn'    ] = $this->getSQN();
		$this->data['info']['repo'   ] = $this->getRepo();
		$this->data['info']['class'  ] = $this->getClass();
		$this->data['info']['name'   ] = $this->getName();
		if(!gfa($this->data,'info','version'))
			$this->data['info']['version'] = DEFAULT_VERSION;
		return $this;
	}

}
