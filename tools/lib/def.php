<?php

require_once(ROOT.'/tools/lib/tml.php');

abstract class Def {

	//main data access
	public $data     = null;

	//internal data handlers
	protected $filename = null;
	protected $dataMD5  = null;
	protected $dataRaw  = null;

	protected $container_var = 'def';

	//io handling
	const READONLY  = 1;
	const READWRITE = 0;
	public $iostate = self::READONLY; // read-only by default

	//parser
	const PARSER_NATIVE = 0;
	const PARSER_TML = 1;
	public $parser = self::PARSER_NATIVE;

	function __destruct(){
		$this->write();
	}

	public function __toString(){
		return $this->dataRaw;
	}

	public function get(){
		return mda_get($this->data,func_get_args());
	}

	public function getFilename(){
		return $this->filename;
	}

	//add a single item to a def
	// - val should be an array that matches what it should be in the def
	public function add($val){
		if(!is_array($val) || !count($val)) throw new Exception('Invalid value to add: '.print_r($val,true));
		$def = array_merge($this->data,$val);
		$this->data = $def;
		unset($def);
		return $this;
	}

	//deletes a single item from a def
	// - val should be a key path eg: var.foo.bar
	public function del($val){
		mda_del($this->data,$val);
		return $this;
	}

	//update a value in a def (can be a whole subsection)
	// - val shold be an array that matches what it should be in the def
	public function update($val){
		return $this->add($val);
	}

	protected function read(){
		if((!file_exists($this->filename)) || (($filesize = filesize($this->filename))===0)) $this->sync();
		$md5 = md5($this->dataRaw);
		if($this->dataMD5 != $md5){
			$fh = fopen($this->filename,'r');
			if(is_resource($fh) && ($def_data = fread($fh,$filesize))){
				$this->dataRaw = $def_data;
				//check for native format
				if(stripos($this->dataRaw,'<?php') !== false){
					$this->parser = self::PARSER_NATIVE;
					$proc = proc_open('php',array(0=>array('pipe','r'),1=>array('pipe','w')),$p);
					if(is_resource($proc)){
						fwrite($p[0],$def_data . 'print(serialize($'.$this->container_var.'));');
						fclose($p[0]);
						$this->data = unserialize(stream_get_contents($p[1]));
						fclose($p[1]);
						proc_close($proc);
					}
					fclose($fh);
				//tml format
				} else {
					$this->parser = self::PARSER_TML;
					$this->data = mda_get(TML::toArray($this->dataRaw),$this->container_var);
				}
			}
		}
		$this->dataMD5 = md5($this->dataRaw);
		return $this->dataSanitizer();
	}

	protected function sync(){
		$this->dataSanitizer();
		$this->data['defparser']=(!is_null(mda_get($this->data,'defparser')))?$this->data['defparser']:$this->parser;
		switch($this->data['defparser']){
			case self::PARSER_NATIVE:
				$this->dataRaw = <<<'HEADER'
<?php
// THIS IS A VOLATILE FILE AND MAY BE REGENERATED AUTOMATICALLY


HEADER;
				$this->dataRaw .= '$'.$this->container_var.' = array();'."\n";
				$this->dataRaw .= self::dumpDef($this->data);
				$this->dataRaw .= PHP_EOL;
				break;
			case self::PARSER_TML:
				$this->dataRaw = TML::fromArray(array($this->container_var=>$this->data));
				break;
			default:
				throw new Exception('Invalid def parser',ERR_DEF_PARSER_INVALID);
				break;
		}
		$this->dataMD5 = md5($this->dataRaw);
		return $this;
	}

	protected function write(){
		$this->sync();
		if($this->iostate === self::READONLY) return $this;
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
		ksort($def);
		$predef = array();
		foreach(array('defparser','ui','target','mirror') as $t){
			if(!is_null(mda_get($def,$t))){
				$predef[$t] = $def[$t];
				unset($def[$t]);
			}
		}
		foreach(array_merge($predef,$def) as $def_key => $def_val){
			if(is_array($def_val)){
				$output .= sprintf('$%s%s[\'%s\'] = array();'.PHP_EOL,$this->container_var,$parent_nodes,$def_key);
				$output .= self::dumpDef($def_val,array_merge($parents,array($def_key)));
			} else {
				if(!is_numeric($def_key)) $def_key = "'".$def_key."'";
				$output .= sprintf('$%s%s[%s] = \'%s\';'.PHP_EOL,$this->container_var,$parent_nodes,$def_key,$def_val);
			}
		}
		return $output;
	}

}
