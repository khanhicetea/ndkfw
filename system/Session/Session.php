<?php
namespace Session;
defined('DS') or die();

use \Http\Request;

class Session {
    
    private static $instance = null;
    
    private function __construct() {
        // No code here
    }
    
    private function __clone() {
        // No code here
    }
    
    private function __wakeup() {
        // No code here
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }

    public function start($name = 'NDK', $limit = 0, $path = '/', $domain = null, $secure = null) {
        session_name($name . '_SESSION');

        $domain = isset($domain) ? $domain : Request::getServerName();
        $https = isset($secure) ? $secure : isset($_SERVER['HTTPS']);

        session_set_cookie_params($limit, $path, $domain, $https, true);
        session_start();

        if (empty($_SESSION['_NDK_SESSION_TOKEN_'])) {
            $_SESSION['_NDK_SESSION_TOKEN_'] = base64_encode(Request::getRemoteAddr() . Request::getHttpUserAgent());
        } elseif ($_SESSION['_NDK_SESSION_TOKEN_'] != base64_encode(Request::getRemoteAddr() . Request::getHttpUserAgent())) {
            self::destroy();

            session_start();
            session_regenerate_id(true);

            __error__('Your session has been terminated !');
        }
    }

    public function destroy() {
        session_unset();
        session_destroy();
    }

    public function get($key) {
        if (array_key_exists('NDK_' . $key, $_SESSION)) {
            return $_SESSION['NDK_' . $key];
        }

        return null;
    }

    public function set($key, $value, $overwrite = false) {
        if ($overwrite || !array_key_exists('NDK_' . $key, $_SESSION)) {
            $_SESSION['NDK_' . $key] = $value;
        }
    }

    public function remove($key) {
        unset($_SESSION['NDK_' . $key]);
    }

    public function setFlash($key, $value) {
        $_SESSION['FLASH_NEW'][$key] = $value;
    }

    public function getFlash($key) {
        if (isset($_SESSION['FLASH_OLD']) && array_key_exists($key, $_SESSION['FLASH_OLD'])) {
            return $_SESSION['FLASH_OLD'][$key];
        }

        return null;
    }

    public function transferFlash() {
        $tmp = isset($_SESSION['FLASH_NEW']) ? $_SESSION['FLASH_NEW'] : array();
        unset($_SESSION['FLASH_OLD']);
        unset($_SESSION['FLASH_NEW']);
        $_SESSION['FLASH_OLD'] = $tmp;
    }

}
