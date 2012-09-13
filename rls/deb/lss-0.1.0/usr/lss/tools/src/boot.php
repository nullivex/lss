<?php

define('ROOT',dirname(dirname(dirname(__FILE__))));
chdir(ROOT);

//constants
define('LSSTOOLS_VERSION','0.1.0');
define('REPO_MAIN','main');
define('DEF_PATH','pkg');
define('PKG_PATH','pkg');
define('DEFAULT_UI',0); //can't use UI class const yet
define('DEFAULT_VERSION','0.0.1');
define('DEFAULT_DESCRIPTION','An OpenLSS Package');
define('DEFAULT_TARGET','/opt/lss');
define('DEFAULT_LSS','/usr/lss');
define('DEFAULT_CACHE','/var/cache/lss');
define('PACKAGE_DB_FQN','main/sys/db');

//load global src files
require_ONCE(ROOT.'/tools/src/err.php');
require_once(ROOT.'/tools/src/func.php');
require_once(ROOT.'/tools/src/mda.php');

//load global objects
require_once(ROOT.'/tools/lib/lsdef.php');
require_once(ROOT.'/tools/lib/usrdef.php');
require_once(ROOT.'/tools/lib/ui.php');

//start lsdef for handle system wide default options
LsDef::init();

//start the usrdef to handle default options
UsrDef::init((isset($_SERVER['HOME']) ? $_SERVER['HOME'] : null));

//load the User Interface
UI::init(UsrDef::_get()->get('ui'),'OpenLSS v'.LSSTOOLS_VERSION);

//setup signal catching
declare(ticks = 1);
function sig_handler($signo){
	switch($signo){
		case SIGWINCH:
		case SIGHUP:
			UI::resize();
			break;
		case SIGINT:
		case SIGTERM:
			UI::_get()->__destruct_by_signal();
			exit(2);
			break;
	}
}
pcntl_signal(SIGWINCH,"sig_handler");
pcntl_signal(SIGHUP  ,"sig_handler");
pcntl_signal(SIGTERM ,"sig_handler");
pcntl_signal(SIGINT  ,"sig_handler");

//setup exception handling
function sysError($e){
	$msg = 'ERROR['.$e->getCode().'@'.$e->getFile().':'.$e->getLine().']: '.$e->getMessage()."\n";
	UI::out($msg,true);
}
set_exception_handler('sysError');
