#!/usr/bin/php
<?php

//figure out where we are
define('ROOT',dirname(dirname(__FILE__)));

//require sources
require_once('bin/lib/phpkg.php');

echo "generateManifest():\n";
$manifest = generateManifest('seed/test');
var_dump($manifest);
echo "\n";

echo "writeManifest():\n";
var_dump(writeManifest('seed/test',$manifest,true));
echo "\n";

echo "readManifest():\n";
var_dump(readManifest('seed/test'));
echo "\n";
