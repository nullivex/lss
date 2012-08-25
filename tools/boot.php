<?php

define('ROOT',dirname(dirname(__FILE__)));
chdir(ROOT);

//constants
define('LSSTOOLS_VERSION','0.1.0');
define('REPO_MAIN','main');
define('DEF_PATH','pkg/def');
define('PKG_PATH','pkg');
define('DEFAULT_UI',0); //can't use UI class const yet
define('DEFAULT_VERSION','0.0.1');
define('DEFAULT_DESCRIPTION','An OpenLSS Package');
define('DEFAULT_TARGET','/opt/lss');
define('DEFAULT_LSS','/usr/lss');
define('DEFAULT_MIRROR','/usr/lss/pkg');
define('DEFAULT_CACHE','/var/cache/lss');
define('DEFAULT_HOOKS','/var/spool/lss');

//load global deps
require_once(ROOT.'/tools/lib/func.php');
require_once(ROOT.'/tools/lib/usrdef.php');
require_once(ROOT.'/tools/lib/ui.php');

//start the usrdef to handle default options
UsrDef::init($_SERVER['USER']);

//load the User Interface
UI::init(UsrDef::_get()->get('ui'));

//setup exception handling
function sysError($e){
	$msg = 'ERROR['.$e->getCode().'@'.$e->getFile().':'.$e->getLine().']: '.$e->getMessage()."\n";
	UI::out($msg,true);
}
set_exception_handler('sysError');
