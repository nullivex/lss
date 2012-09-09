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
		$tmpfile = tempnam(sys_get_temp_dir(),'lss-extract_');
		copy($archive,$tmpfile);
		$p = new PharData($tmpfile);
		if(isset($p['.lss'])) unset($p['.lss']);
		$rv = $p->extractTo($dest,null,true);
		unset($p);
		PharData::unlinkArchive($tmpfile);
		return $rv;
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

	//this handles mirror authorization more gracefully than the core function
	//	also has some nice error printing for nice warnings
	public static function getFromMirror($url,$err_verbiage=null){
		$buff = false;
		try {
			$buff = mirror_get_contents($url);
		} catch(Exception $e){
			if($e->getCode() == ERR_MIRROR_AUTH_FAILED || $e->getCode() == ERR_MIRROR_INVALID)
				UI::out('WARNING: '.(is_null($err_verbiage) ? '' : $err_verbiage.' ').$e->getMessage()."\n",true);
			else throw $e;
		}
		return $buff;
	}

	public static function getMirrors(){
		$mirrors = array();
		self::_mergeMirrors($mirrors,LsDef::_get()->get('mirror'));
		self::_mergeMirrors($mirrors,UsrDef::_get()->get('mirror'));
		self::_mergeMirrors($mirrors,TgtDef::_get()->get('mirror'));
		krsort($mirrors);
		return $mirrors;
	}

	private static function _mergeMirrors(&$mirrors,$arr){
		foreach($arr as $weight => $mirror){
			if(in_array($mirror,$mirrors)) continue;
			$mirrors[$weight] = $mirror;
		}
	}

	public static function v2b($version){
		$bin = null; $parts = explode('.',$version);
		if(count($parts) > 3) throw new Exception('Versions are only allowed 3 octets',ERR_INVALID_VERSION);
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

	//check if a certain package is in an install set
	public static function isSelected($fqn,$pkgs){
		foreach($pkgs as $pkg){
			if($pkg['fqn'] != $fqn) continue;
			return true;
		}
		return false;
	}

}
