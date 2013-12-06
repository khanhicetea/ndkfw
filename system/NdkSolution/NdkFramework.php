<?php
namespace NdkSolution;
use Database\Db;
defined('DS') or die();

class NdkFramework {
    
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
    
    private function loadRegistry() {
        \Registry::set('config', \NdkSolution\Config::getInstance());
        \Registry::set('session', \Session\Session::getInstance());
        \Registry::set('security', \Security\Security::getInstance());
    }
    
    private function loadConfig() {
        \Database\Db::attachDriver('mysql', new \Database\Adapter\Mysql('localhost', 'root', '123abcxyz', 'ltvn'));
    }
    
    public function run() {
        $this->loadRegistry();
        $this->loadConfig();
        __('session')->start();
        
        
        __('config')->loadFromDb('system_settings', 'key', 'value');
        echo __('config')->get('db.title');
    }
    
    public function __destruct() {
        __('session')->transferFlash();
    }
    
}