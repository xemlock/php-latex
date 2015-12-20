<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);

// find autoload.php moving upwards, so that tests can be executed
// even if the library itself lies in the vendor/ directory of another
// project

$dir = dirname(__FILE__);
$autoload = null;

while ($parent = realpath($dir . '/..')) {
    if (file_exists($path = $parent . '/vendor/autoload.php')) {
        $autoload = $path;
        break;
    }
    $dir = $parent;
}
if (empty($autoload)) {
    die('Unable to find autoload.php');
}

require_once $autoload;
