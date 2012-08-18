<?php
//require sources
require_once('tools/lib/func.php');

//add a single item to a def
// - val should be an array that matches what it should be in the def
function defAdd($pkg,$val,$repo=REPO_MAIN){
	if(!is_array($val) || !count($val)) throw new Exception('Invalid value to add: '.print_r($val,true));
	$def = readDef($pkg,$repo);
	return writeDef($pkg,ksort(array_merge($def,$val)));
}

//update a value in a def (can be a whole subsection)
// - val shold be an array that matches what it should be in the def
function defUpdate($pkg,$val,$repo=REPO_MAIN){
	return defAdd($pkg,$val,$repo);
}

//deletes a single item from a def
// - val should be a key definition as a string: ex "['keyname']['anothername']['etc']"
function defDel($pkg,$val,$repo=REPO_MAIN){
	$def = readDef($pkg,$repo);
	dfa($def,$val);
	return writeDef($pkg,$def,$repo);
}

function readDef($pkg = ''){
// returns parsed content, or an empty array on any error
	if($pkg == '') return false;
	$def_file = DEF_PATH.$pkg.".lss";
	if(file_exists($def_file) && $fh = fopen($def_file,'r'))
		return parseDef(fread($fh,filesize($def_file)));
	return array();
}

function parseDef($def_data = ''){
// returns a populated array, or empty array on any error
	$pkgdef = array();
	if($def_data != ''){
		$proc = proc_open('php',array(0=>array('pipe','r'),1=>array('pipe','w')),$p);
		if(is_resource($proc)){
			fwrite($p[0],$def_data . 'print(serialize($pkgdef));');
			fclose($p[0]);
			$pkgdef = unserialize(stream_get_contents($p[1]));
			fclose($p[1]);
			proc_close($proc);
		}
	}
	return $pkgdef;
}

function writeDef($pkg_dir = '',$def = false,$clobber = false){
// returns true if content was written, or false on any error
// optional $clobber allows overwriting file if it exists (otherwise fails)
	if(!is_array($def) || count($def) === 0) return false;
	$def_file = DEF_PATH.$pkg.".lss";
	if(file_exists($def_file))
		if($clobber)
			run('mv '.$def_file.' '.$def_file.'.bak'); // why not save the previous version
		else
			return false;
	$fh = fopen($def_file,'w');
	fprintf($fh,'<?php'."\n");
	fprintf($fh,'// THIS IS A VOLATILE FILE AND MAY BE REGENERATED AUTOMATICALLY'."\n"."\n");
	fprintf($fh,'$pkgdef = array();'."\n");
	dumpDef($fh,$def);
	fprintf($fh,"\n");
	fclose($fh);
	return true;
}

function dumpDef($fh=null,$def=false,$parents=array()){
	if(!is_resource($fh)) throw new Exception('dumpDef file handle invalid');
	$parent_nodes = (count($parents) != 0) ? '['.join($parents,'][').']' : '';
	foreach($def as $def_key => $def_val){
		if(is_array($def_val)){
			fprintf($fh,'$pkgdef%s[\'%s\'] = array();'."\n",$parent_nodes,$def_key);
			array_unshift($parent_nodes,$def_key);
			dumpDef($fh,$def_val,$parent_nodes);
		} else {
			fprintf($fh,'$pkgdef%s[\'%s\'] = \'%s\';'."\n",$parent_nodes,$def_key,$def_val);
		}
	}
}
