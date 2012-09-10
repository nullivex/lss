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
		$tmpfile = sys_get_temp_dir().'/lss-extract_'.uniqid().'.tar.bz2';
		copy($archive,$tmpfile);
		$p = new PharData($tmpfile,null,null,Phar::TAR);
		try {
			$p->delete('.lss'); //remove the def file for extraction
		} catch(Exception $e){}
		$rv = $p->extractTo($dest,null,true);
		unset($p);
		if(!PharData::unlinkArchive($tmpfile)) throw new Exception('Failed to remove temporary pkg',ERR_PKG_TMP_DEL_FAILED);
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
	
	public static function download($pkg,&$file=null){
		//setup paths
		$dest = $file = CACHE.'/mirror/'.$pkg['fqn'].'-'.$pkg['version'].'.tar.bz2';
		$src = $pkg['mirror'].'/'.$pkg['fqn'].'-'.$pkg['version'].'.tar.bz2';
		$sumsrc = $pkg['mirror'].'/'.$pkg['fqn'].'-'.$pkg['version'].'.sums';
		//check if we have the package in cache
		if(file_exists($dest)){
			//confirm checksum of the file
			$buff = file_get_contents($dest);
			$sums = file_get_contents($sumsrc);
			if(!preg_match('/md5\s+(.{32})/si',$sums,$m)) throw new Exception('Could not find pkg sum',ERR_PKG_SUM_MISSING);
			if(!isset($m[1])) throw new Exception('Could not match pkg sum',ERR_PKG_SUM_MISSING);
			$src_pkgsum = $m[1]; unset($m);
			UI::out("\nSource chksum: $src_pkgsum\n",OUT_DEBUG);
			$pkgsum = md5($buff);
			UI::out("Local chksum: $pkgsum\n",OUT_DEBUG);
			if($src_pkgsum != $pkgsum){
				UI::out("Local package is different removing for redownload\n",OUT_DEBUG);
				unlink($dest);
				$buff = false;
			} else {
				//we have a good local package cached lets use that
				UI::out("Using local cached package\n",OUT_DEBUG);
				return $buff;
			}
		}
		//download package
		UI::out("About to download package from source\n",OUT_DEBUG);
		$buff = Pkg::getFromMirror(
			$src, //url to download from
			'Could not download package: '.$src //err message pre-text
		);
		if($buff === false) continue;
		//create dest dir
		@mkdir(dirname($dest),0755,true);
		//write package
		$rv = @file_put_contents($dest,$buff);
		if(!$rv) throw new Exception('Failed to save package: '.$dest,ERR_PKG_SAVE_FAILED);
		return $buff;
	}

	//this handles mirror authorization more gracefully than the core function
	//	also has some nice error printing for nice warnings
	public static function getFromMirror($url,$err_verbiage=null){
		$buff = false;
		try {
			$buff = mirror_get_contents($url);
		} catch(Exception $e){
			if($e->getCode() == ERR_MIRROR_AUTH_FAILED || $e->getCode() == ERR_MIRROR_INVALID)
				UI::out('WARNING: '.(is_null($err_verbiage) ? '' : $err_verbiage.' ').$e->getMessage()."\n",OUT_WARN);
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
