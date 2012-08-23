<?php

define('ROOT',dirname(dirname(__FILE__)));
chdir(ROOT);

//constants
define('REPO_MAIN','main');
define('DEF_PATH','pkg/def');
define('PKG_PATH','pkg');
define('DEFAULT_VERSION','0.0.1');
define('DEFAULT_TARGET','/opt/lss');
define('DEFAULT_LSS','/usr/lss');
define('DEFAULT_MIRROR','/usr/lss/pkg');
define('DEFAULT_CACHE','/var/cache/lss');
define('DEFAULT_HOOKS','/var/spool/lss');

//setup exception handling
function sysError($e){
	echo 'ERROR['.$e->getCode().'@'.$e->getFile().':'.$e->getLine().']: '.$e->getMessage()."\n";
}
set_exception_handler('sysError');
