<?php

function pkgExists($pkg,$repo=REPO_MAIN,$root=ROOT){
	if(file_exists($root.'/pkg/'.$repo.'/'.$pkg)) return true;
	return false;
}
