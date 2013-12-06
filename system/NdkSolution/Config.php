<?php
namespace NdkSolution;
defined('DS') or die();

class Config {
    
    private $data = array();
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

    public function loadFile($file_name) {
        $file_path = APPLICATION_PATH . 'config' . DS . $file_name . '.php';

        $this->data[$file_name] = __load__($file_path, TRUE);
    }

    public function get($key) {
        $keys = explode('.', $key);
        if (isset($this->data[$keys[0]][$keys[1]])) {
            return $this->data[$keys[0]][$keys[1]];
        }

        return null;
    }

    public function set($key, $value, $overwrite = TRUE) {
        $keys = explode('.', $key);
        if ($overwrite || !isset($this->data[$keys[0]][$keys[1]])) {
            $this->data[$keys[0]][$keys[1]] = $value;
        }
    }

    public function loadFromDb($table, $key_field, $value_field, $db_connection = null) {
        $config = new \Model\SqlConfig($db_connection);
        $new_data = $config->getAll($table, $key_field, $value_field);

        if (empty($this->data['db'])) {
            $this->data['db'] = $new_data;
        } else {
            $this->data['db'] = array_merge($this->data['db'], $new_data);
        }
    }
}