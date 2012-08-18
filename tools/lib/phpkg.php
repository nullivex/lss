<?php
// phpkg - PHP Packager
define('FILE_MANIFEST','phpkg_manifest.php');

//require sources
require_once('bin/lib/func.php');

function generateManifest($mod_dir = ''){
// returns a populated array, or empty array on any error
	if(($mod_dir == '') || !is_dir($mod_dir . '/')) return array();
	return preg_replace('|^'.$mod_dir.'/|','',file_array($mod_dir,FILE_MANIFEST.'|'.FILE_MANIFEST.'.bak',true));
}

function readManifest($mod_dir = ''){
// returns parsed content, or an empty array on any error
	if($mod_dir == '') return false;
	$mani_file = $mod_dir . '/' . FILE_MANIFEST;
	if(file_exists($mani_file) && $fh = fopen($mani_file,'r'))
		return parseManifest(fread($fh,filesize($mani_file)));
	return array();
}

function writeManifest($mod_dir = '',$mani_data = false,$clobber = false){
// returns true if content was written, or false on any error
// optional $clobber allows overwriting file if it exists (otherwise fails)
	if(!is_array($mani_data) || count($mani_data) === 0) return false;
	$mani_file = $mod_dir . '/' . FILE_MANIFEST;
	if(file_exists($mani_file))
		if($clobber)
			run('mv '.$mani_file.' '.$mani_file.'.bak'); // why not save the previous version
		else
			return false;
	$fh = fopen($mani_file,'w');
	fprintf($fh,'<?php'."\n");
	fprintf($fh,'// THIS IS A VOLATILE FILE AND MAY BE REGENERATED AUTOMATICALLY'."\n"."\n");
	fprintf($fh,'$manifest = array();'."\n");
	foreach($mani_data as $mani_item) fprintf($fh,'$manifest[] = \'%s\';'."\n",$mani_item);
	fprintf($fh,"\n");
	fclose($fh);
	return true;
}

function parseManifest($mani_data = ''){
// returns a populated array, or empty array on any error
	$manifest = array();
	if($mani_data != ''){
		$proc = proc_open('php',array(0 => array("pipe","r"),1 => array("pipe","w")),$p);
		if(is_resource($proc)){
			fwrite($p[0],$mani_data . 'print(serialize($manifest));');
			fclose($p[0]);
			$manifest = unserialize(stream_get_contents($p[1]));
			fclose($p[1]);
			proc_close($proc);
		}
	}
	return $manifest;
}
