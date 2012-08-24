<?php

class PkgExport {

	private $def = null;
	private $buff = null;

	public function __construct($def){
		$this->def = $def;
	}
	
	//make sure the def is readonly (no funny business)
	public function getDef(){
		return $this->def;
	}
	
	//we compile the tarball into memory
	public function compile(){
		//build the file mapping array
		$filemap = array();
		foreach($this->def->data['manifest'] as $file)
			$filemap[$file] = Pkg::pkgPath($this->def->getSQN()).'/'.$file;
		$n = '/dev/shm/php_'.time().'.tar';
		$p = new PharData($n);
		$p->startBuffering();
		$p->buildFromIterator(new ArrayIterator($filemap));
		$p->stopBuffering();
		unset($p);
		$this->buff = file_get_contents($n);
		Phar::unlinkArchive($n);
	}
	
	//now compress that tarball
	public function compress(){
		$n = '/dev/shm/php_'.time().'.tar';
		file_put_contents($n,$this->buff);
		$p = new PharData($n);
		$q = $p->compress(Phar::BZ2);
		unset($p,$q);
		Phar::unlinkArchive($n);
		$this->buff = file_get_contents($n.'.bz2');
		Phar::unlinkArchive($n.'.bz2');
	}
	
	//write the tarball to disk
	public function write($dest){
		@mkdir(dirname($dest),0755,true);
		file_put_contents($dest,$this->buff);
	}
	
	//retrieve the buffer for external use
	public function get(){
		return $this->buff;
	}
	
	public static function getDest($def){
		return MIRROR.'/'.$def->getFQN().'.tar.bz2';
	}
	
}
