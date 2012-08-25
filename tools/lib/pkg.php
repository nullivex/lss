<?php

class Pkg {

	public static function exists($pkg,$repo=REPO_MAIN,$root=ROOT){
		if(file_exists(self::pkgPath($pkg,$repo,$root))) return true;
		return false;
	}
	
	public static function create($pkg,$repo=REPO_MAIN,$root=ROOT){
		@mkdir(self::pkgPath($pkg,$repo,$root),0755,true);
		return true;
	}

	public static function extract($archive,$dest){
		$p = new PharData($archive);
		return $p->extractTo($dest,null,true);
	}
	
	public static function pkgPath($pkg,$repo=REPO_MAIN,$root=ROOT){
		return $root.'/'.PKG_PATH.'/'.$repo.'/'.$pkg;
	}
	
	public static function FQNasFile($fqn){
		return str_replace('/','#',$fqn);
	}
	
	public static function hookFile($fqn){
		return TARGET.'/hooks.d/'.Pkg::FQNasFile($fqn).'.php';
	}
	
	public static function v2b($version){
		$bin = null; $parts = explode('.',$version);
		if(count($parts) > 3) throw new Exception('Versions are only allowed 3 octets');
		while(count($parts) < 3) array_unshift($parts,'0');
		foreach($parts as $p) $bin .= str_pad(decbin(intval(trim($p))),8,0,STR_PAD_LEFT);
		return $bin;
	}
	
	public static function v2i($version){
		return bindec(self::v2b($version));
	}
	
	public static function i2v($int){
		$ints = array();
		foreach(str_split(str_pad(decbin($int),24,0,STR_PAD_LEFT),8) as $p) $ints[] = bindec($p);
		return implode('.',$ints);
	}
	
}
