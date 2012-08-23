<?php

abstract class def {

	//main data access
	public $data     = null;

	//internal data handlers
	protected $filename = null;
	protected $dataMD5  = null;
	protected $dataRaw  = null;
	
	//io handling
	const READONLY  = true;
	const READWRITE = false;
	private $iostate = self::READONLY; // read-only by default
	
	function __destruct(){
		$this->write();
	}

	public function __toString(){
		return $this->dataRaw;
	}
	
	//add a single item to a def
	// - val should be an array that matches what it should be in the def
	public static function add($val){
		if(!is_array($val) || !count($val)) throw new Exception('Invalid value to add: '.print_r($val,true));
		$def = array_merge($this->data,$val);
		$this->data = $def;
		unset($def);
		return $this;
	}

	//deletes a single item from a def
	// - val should be a key definition as a string: ex "['keyname']['anothername']['etc']"
	public static function del($val){
		dfa($this->data,$val);
		return $this;
	}

	//update a value in a def (can be a whole subsection)
	// - val shold be an array that matches what it should be in the def
	public static function update($val){
		return $this->add($val);
	}

	protected function read(){
		if((!file_exists($this->filename)) || (($filesize = filesize($this->filename))===0)) $this->write();
		$md5 = md5_file($this->filename);
		if($this->dataMD5 != $md5){
			$fh = fopen($this->filename,'r');
			if(is_resource($fh) && ($def_data = fread($fh,$filesize))){
				$this->dataRaw = $def_data;
				$proc = proc_open('php',array(0=>array('pipe','r'),1=>array('pipe','w')),$p);
				if(is_resource($proc)){
					fwrite($p[0],$def_data . 'print(serialize($pkgdef));');
					fclose($p[0]);
					$this->data = unserialize(stream_get_contents($p[1]));
					fclose($p[1]);
					proc_close($proc);
				}
				fclose($fh);
			}
		}
		$this->dataMD5 = md5($this->dataRaw);
		return $this->dataSanitizer();
	}

	protected function sync(){
		$this->dataSanitizer();
		$this->dataRaw = <<<'HEADER'
<?php
// THIS IS A VOLATILE FILE AND MAY BE REGENERATED AUTOMATICALLY

$pkgdef = array();

HEADER;
		$this->dataRaw .= self::dumpDef($this->data);
		$this->dataRaw .= PHP_EOL;
		$this->dataMD5 = md5($this->dataRaw);
		return $this;
	}
	
	protected function write(){
		$this->sync();
//		if($this->iostate === self::READONLY) return $this;
		if(file_exists($this->filename) && (filesize($this->filename) !== 0))
			run('mv '.$this->filename.' '.$this->filename.'.bak'); // why not save the previous version
		@mkdir(dirname($this->filename),0755,true);
		$fh = fopen($this->filename,'w');
		fwrite($fh,$this->dataRaw);
		fclose($fh);
		return $this;
	}
	
	protected function dataSanitizer(){
		//this is to be implemented by the extender
	}

//
// NOTE - This needs to call itself, with copies of the array because of the method used, so they stay in the args
//
	protected function dumpDef($def=array(),$parents=array()){
		$output = '';
		$parent_nodes = (count($parents)) ? "['".join("']['",$parents)."']" : '';
	//	ksort($def);
		foreach($def as $def_key => $def_val){
			if(is_array($def_val)){
				$output .= sprintf('$pkgdef%s[\'%s\'] = array();'.PHP_EOL,$parent_nodes,$def_key);
				$output .= self::dumpDef($def_val,array_merge($parents,array($def_key)));
			} else {
				if(!is_numeric($def_key)) $def_key = "'".$def_key."'";
				$output .= sprintf('$pkgdef%s[%s] = \'%s\';'.PHP_EOL,$parent_nodes,$def_key,$def_val);
			}
		}
		return $output;
	}

}