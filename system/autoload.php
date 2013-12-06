<?php
defined('DS') or die();

final class Registry {
    
    private $instances = null;
    private static $registry = null;
    
    private function __construct() {
        $this->instances = array();
    }
    
    private function __clone() {
        // No code here
    }
    
    private function __wakeup() {
        // No code here
    }
    
    public static function getInstance() {
        if (self::$registry === null) {
            self::$registry = new self();
        }
        
        return self::$registry;
    }
    
    public static function set($key_name, $instance, $overwrite = FALSE) {
        $registry = self::getInstance();
        if ($overwrite || !array_key_exists($key_name, $registry->instances)) {
            $registry->instances[$key_name] = $instance;
        }
    }
    
    public static function get($key_name) {
        $registry = self::getInstance();
        return array_key_exists($key_name, $registry->instances) ? $registry->instances[$key_name] : null;
    }
    
}

class Autoloader {
    
    private $dirs = array();

    public function __construct(Array $dirs) {
        $this->dirs = $dirs;
    }
    
    public function load($class_name = '') {
        $class_path = ltrim(str_replace('\\', DS, $class_name), '\\') . '.php';
        
        foreach ($this->dirs as $dir) {
            if (is_readable($dir . $class_path)) {
                include ($dir . $class_path);
                return TRUE;
            }
        }
        
        __error__('Class `' . $class_name . '` is not found !');
    }
    
    public function init() {
        spl_autoload_register(array($this, 'load'), TRUE);
    }

}

$autoloader = new Autoloader( [SYSTEM_PATH, APPLICATION_PATH] );
$autoloader->init();

function __($name) {
    return \Registry::get($name);
}