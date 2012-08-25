<?php

interface UIInt {

	public static function init($type);
	public static function _get();
	public static function out($string,$err=false);
	public function ask($q,$a,$default=false);
	public function input($q,&$a);
	public function password($q,&$a);
	public function select($q,&$a,$default=0);

}

abstract class UI {
	//base user interface object
	static $inst   = false;
	public $handle = false;
	
	static $debug = false;

	//ui-type handling
	const TEXT    = 0;
	const TEXT_DESC = 'Text';
	const MENU = 1;
	const MENU_DESC = 'Menu';
	const HTML    = 2;
	const HTML_DESC = 'HTML';
	public $type = self::TEXT; // simplest interface type by default
	
	public static function init($type=self::TEXT){
		switch($type){
			case self::TEXT:
				require_once(ROOT.'/tools/lib/ui_text.php');
				self::$inst = new UIText();
				break;
			case self::MENU:
				require_once(ROOT.'/tools/lib/ui_menu.php');
				self::$inst = new UIMenu();
				break;
			case self::HTML:
				require_once(ROOT.'/tools/lib/ui_html.php');
				self::$inst = new UIHTML();
				break;
		}
		self::$inst->type = $type;
		self::$inst->_init();
	}
	
	public static function _get(){
		if(!is_object(self::$inst)) throw new Exception('UI has not been initialized');
		return self::$inst;
	}
	
	public static function switchType($type=self::TEXT){
		self::$inst->_deinit();
		self::init($type);
	}
	
	public function __destruct(){
		$this->_deinit();
	}

	public function __toString(){
		switch($this->type){
			case self::TEXT:	return self::TEXT_DESC;
			case self::MENU:	return self::MENU_DESC;
			case self::HTML:	return self::HTML_DESC;
		}
	}

	// out(string,err) : general output (print / echo)
	// - string : the output
	// - err    : if false, output normally (STDOUT or equivalent) [default]
	//            if true, output as error (STDERR or equivalent)
	private function _out($string,$err=false){
		//uses out() as implemented by the extender
		$newline = (substr($string,-1) == "\n") ? true : false;
		$a = (is_array($string)) ? $string : split("\n",$string);
		if(($a[max(array_keys($a))]=='')) unset($a[max(array_keys($a))]);
		foreach(array_keys($a) as $k)
			if($k !== max(array_keys($a))) $a[$k] .= "\n";
			else if($newline)  $a[$k] .= "\n";
		foreach($a as $line) $this->__out($line,$err);
		return $this;
	}
	
	public static function out($string,$err=false){
		return self::$inst->_out($string,$err);
	}
	
	/*
		if(self::$inst == false) self::$inst = new UI($type);
		if(($type !== false) && (self::$inst->type != $type)){
			self::$inst->handle->deinit();
			self::$inst->__construct($type);
		}
		return self::$inst;
	}*/

	// ask(q,a) : ask a true/false question (or yes/no)
	// - q       : the question string ("do you like grits?")
	// - a       : an array such as array('true','false') or array('y','n')
	// - default : which answer is default (optional)
	// returns true or false based on user input (a[0] is always the truth value)
	//public function ask($q,$a,$default=false){
		//this is to be implemented by the extender
		//return $this->handle->ask($q,$a,$default);
	//}

	// input(q,a) : ask for a freeform value (like HTML INPUT field)
	// - q should be the question string ("what is your username?")
	// - a if != null, is displayed as the default answer
	//     if == null, a sane default (from the pkgdef) is preselected
	//     else, displayed as the default answer (editable)
	//     on exit, set by reference to whatever string the user inputs
	//public function input($q,&$a){
		//this is to be implemented by the extender
		//return $this->handle->input($q,&$a);
	//}

	// password(q,a) : ask for a freeform value but hidden (like HTML INPUT TYPE=PASSWORD field)
	// - q should be the question string ("enter your password, heathen:")
	// - a if != null, is displayed as the default answer
	//     if == null, a sane default (autogenned randomly) is preselected and unmasked
	//     else, displayed as the default answer (editable)
	//     on exit, set by reference to whatever string the user inputs
	//public function password($q,&$a){
		//this is to be implemented by the extender
		//return $this->handle->password($q,&$a);
	//}

	// select(q,a) : one-of-many box (like HTML SELECT)
	// - q should be the question string ("select a flavor of syrup:")
	// - a is an array of answers like array('optiontext'=>'optionvalue')
	// returns the user-selected option value
	//public function select($q,$a){
		//this is to be implemented by the extender
		//return $this->handle->select($q,$a);
	//}

}
