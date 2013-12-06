<?php
defined('DS') or die();

// Get PHP Version

if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

// Check PHP Version

if (PHP_VERSION_ID < 50300) {
    __error__('Please upgrade PHP Version to 5.3 or higher !');
}

define('ENV_DEVELOPMENT', 'development');
define('ENV_PRODUCTION', 'production');

define('ENVIRONMENT', ENV_DEVELOPMENT);

define('APPLICATION_DIR', 'application');
define('SYSTEM_DIR', 'system');

define('APPLICATION_PATH', ROOT . APPLICATION_DIR . DS);
define('SYSTEM_PATH', ROOT . SYSTEM_DIR . DS);

// System functions

function __error__($message) {
    if (ENVIRONMENT === ENV_PRODUCTION) {
        $message = 'We have something wrong here !';
    }
    
    $html = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=utf-8"/><meta name="robots" content="noindex,nofollow"/><title>Ooppps ...</title><style>body{background: #f9fee8;margin: 0;padding: 20px;text-align:center;font-family:sans-serif;font-size:14px;color:#666666;}.error_page{width: 600px;padding: 50px;margin: auto;}.error_page h1{margin: 20px 0 0;}.error_page p{margin: 10px 0;padding: 0;}</style></head><body><div class="error_page"><img src="data:image/png;base64,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" alt="error" /><h1>We\'re sorry...</h1><p>' . $message . '</p></div></body></html>';
    header("HTTP/1.0 404 Not Found");
    echo $html; die();
}

function __dump__($var) {
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}

function __load__($file_path, $return = FALSE) {
    if (!is_readable($file_path)) {
        __error__("File `{$file_path}` is not readable !");
    }
    
    if ($return) {
        return (include $file_path);
    }
    
    include $file_path;
}