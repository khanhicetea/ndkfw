<?php
namespace Logger;
defined('DS') or die();

class Logger {
    
    private $adapters = null;
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
        if (self::$instance  === null) {
            self::$instance = new self;
        }
        
        return self::$instance;
    }
    
    public function attachAdapter($adapter_name, ILogger $logger_adapter) {
        $adapter_name = strtolower($adapter_name);
        $this->adapters[$adapter_name] = $logger_adapter;
    }
    
    public function detachAdapter($adapter_name) {
        $adapter_name = strtolower($adapter_name);
        unset($this->adapters[$adapter_name]);
    }
    
    public function logTrace($msg, $adapter_name = null) {
        if ($adapter_name === null) {
            foreach ($this->adapters as $adapter) {
                $adapter->logTrace($msg);
            }
        } else {
            $adapter_name = strtolower($adapter_name);
            if (array_key_exists($adapter_name, $this->adapters)) {
                $this->adapters[$adapter_name]->logTrace($msg);
            } else {
                throw new Exception("Log adapter `{$adapter_name}` is not attached to Logger !");
            }
        }
    }
    
}