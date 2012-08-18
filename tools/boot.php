<?php

define('ROOT',dirname(dirname(__FILE__)));
chdir(ROOT);

//constants
define('REPO_MAIN','main');
define('DEF_PATH','pkg/def/'); // trailing slash important

//setup exception handling
function sysError($e){
	echo 'ERROR['.$e->getCode().']: '.$e->getMessage()."\n";
}
set_exception_handler('sysError');