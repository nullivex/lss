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
	
	public static function pkgPath($pkg,$repo=REPO_MAIN,$root=ROOT){
		return $root.'/'.PKG_PATH.'/'.$repo.'/'.$pkg;
	}
	
}
