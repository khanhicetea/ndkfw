<?php

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) . DS);
define('CONFIG_DIR', ROOT . 'config' . DS);

if (is_readable(CONFIG_DIR . 'core.php')) {
    include (CONFIG_DIR . 'core.php');
} else {
    echo 'Oopps ...'; die();
}

if (ENVIRONMENT === ENV_DEVELOPMENT) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

include SYSTEM_PATH . 'autoload.php';

NdkSolution\NdkFramework::getInstance()->run();