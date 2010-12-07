<?php
/*
 * Sloppygames - Arcade gaming
 * Light, sturdy, stupid simple
 * (c) Nullivex LLC, All Rights Reserved.
 */

function get($name=false,$value=false){
	if($name === false) return $_GET;
	if($value !== false) $_GET[$name] = $value;
	if(isset($_GET[$name])) return $_GET[$name];
	return null;
}

function post($name=false,$value=false){
	if($name === false) return $_POST;
	if($value !== false) $_POST[$name] = $value;
	if(isset($_POST[$name])) return $_POST[$name];
	return null;
}

function server($name=false,$value=false){
	if($name === false) return $_SERVER;
	if($value !== false) $_SERVER[$name] = $value;
	if(isset($_SERVER[$name])) return $_SERVER[$name];
	return null;
}

function req($name=false,$value=false){
	if($name === false) return $_REQUEST;
	if($value !== false) $_REQUEST[$name] = $value;
	if(isset($_REQUEST[$name])) return $_REQUEST[$name];
	return null;
}

function session($name,$value=false){
	if($value !== false) $_SESSION[$name] = $value;
	elseif(isset($_SESSION[$name])) return $_SESSION[$name];
	else return null;
}

function session_delete(){
	$args = func_get_args();
	foreach($args as $name) unset($_SESSION[$name]);
}

function data($data=false){
	global $_data_set;
	if($data === false) return $_data_set;
	if(is_array($data)) $_data_set = $data;
	if(!is_array($data) && isset($_data_set[$data])) return $_data_set[$data];
	return null;
}

function sysError($msg){
	error($msg);
}

function error($msg){
	echo '<h1>System Error</h1>';
	echo '<p>'.$msg.'</p>';
}

function loginError($msg){
	echo '<div>'.$msg.'</div>';
	echo '<div><a href="'.Url::logout().'">logout</a></div>';
	exit;
}

function page_header(){
	Tpl::_get()->parse('header','header');
}

function page_footer(){
	Tpl::_get()->parse('footer','footer');
}

function output($body){
	ob_end_flush();
	echo $body;
	exit;
}

function redirect($url,$meta=false,$time=2){
	if(empty($url)) $url = Config::get('url','url').'/index.php';
	if(!$meta){
		header("Location: $url");
		output('');
	} else {
		$params = array(
			'url'	=>	$url,
			'time'	=>	$time
		);
		Tpl::_get()->resetBody();
		Tpl::_get()->parse('global','redirect',$params);
		output(Tpl::_get()->output());
	}
}

function run($cmd,&$return=null){
	$output = '';
	$cmd = '/usr/bin/sudo /bin/bash -c "'.addslashes($cmd).'"';
	exec($cmd,$output,$return);
	$output = implode("\n",$output);
	dolog($cmd.': '.$output);
	return $output;
}

function dolog($msg){
	$msg = date('m/d/y g:i:s').' -- '.$msg;
	$handle = fopen(Config::get('paths','log'),'a');
	fwrite($handle,$msg."\n");
	fclose($handle);
}

function alert($msg,$success=true,$delayed=false){
	$class = '';
	if(!$success) $class = 'failure';
	$params['class'] = $class;
	$params['message'] = $msg;
	$alert = Tpl::_get()->getConstant('alert').Tpl::_get()->parse('global','alert',$params,true);
	if($delayed) session('delayed_alert',session('delayed_alert').$alert);
	else Tpl::_get()->setConstant('alert',$alert);
}

function saturate($arr){
	if(!is_array($arr)) return;
	$res = array(); $keys = array_keys($arr);
	foreach($keys as $key) for($i=0;$i<count($arr[key($arr)]);$i++) if(isset($arr[$key][$i])) $res[$i][$key] = $arr[$key][$i];
	return $res;
}

function age($tm,$end=false,$rcs = 0) {
	if(!$end) $end = time();
	$cur_tm = $end; $dif = $cur_tm-$tm;
	$pds = array('second','minute','hour','day','week','month','year','decade');
	$lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
	for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);
	$no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
	if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
	return $x;
}

function hours($tm,$inc=1){
	$hours = round($tm / round((3600 / $inc)));
	if($hours < 1) $hours = 1;
	return $hours.' hours';
}