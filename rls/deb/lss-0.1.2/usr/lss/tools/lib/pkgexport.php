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
		$filemap = array('.lss'=>Pkg::pkgPath($this->def->getSQN()).'/.lss');
		$out = '';
		foreach($this->def->data['manifest'] as $file){
			$file_wpath = Pkg::pkgPath($this->def->getSQN()).'/'.$file;
			$out .= "  Adding file $file_wpath\n";
			$filemap[$file] = $file_wpath;
		}
		$n = '/dev/shm/php_'.time().'.tar';
		$out .= "  Compiling to $n\n";
		$p = new PharData($n);
		$p->startBuffering();
		$p->buildFromIterator(new ArrayIterator($filemap));
		$p->stopBuffering();
		unset($p);
		$this->buff = file_get_contents($n);
		Phar::unlinkArchive($n);
		return $out;
	}

	//now compress that tarball
	public function compress(){
		$n = '/dev/shm/php_'.time().'.tar';
		$out = "  Compressing $n\n";
		file_put_contents($n,$this->buff);
		$p = new PharData($n);
		$q = $p->compress(Phar::BZ2);
		unset($p,$q);
		Phar::unlinkArchive($n);
		$this->buff = file_get_contents($n.'.bz2');
		Phar::unlinkArchive($n.'.bz2');
		return $out;
	}

	//write the tarball to disk
	public function write($dest){
		$out = "  Writing to $dest\n";
		@mkdir(dirname($dest),0755,true);
		file_put_contents($dest,$this->buff);
		$sumfile = preg_replace('/^(.*).tar.bz2$/','$1.sums',$dest);
		$fp = fopen($sumfile,'w');
		foreach(array('crc32b','md5','sha1','whirlpool') as $alg)
			fprintf($fp,"%-10s %s\n",$alg,hash($alg,$this->buff));
		fclose($fp);
		return $out;
	}

	//retrieve the buffer for external use
	public function get(){
		return $this->buff;
	}

	public static function getDest($def,$mirror){
		return $mirror.'/'.$def->getFQN().'-'.$def->getVersion().'.tar.bz2';
	}

}
