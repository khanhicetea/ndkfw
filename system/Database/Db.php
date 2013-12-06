<?php
namespace Database;
defined('DS') or die();

class Db {
    
    private static $drivers = array();
    private static $num_drivers = 0;
    
    public static function attachDriver($name, $driver) {
        self::$drivers[$name] = $driver;
        self::$num_drivers++;
    }
    
    public static function detachDriver($name) {
        if (array_key_exists($name, self::$drivers)) {
            self::$drivers[$name]->disconnect();
            unset(self::$drivers[$name]);
            self::$num_drivers--;
        }
    }
    
    public static function __callStatic($name, $arguments) {
        if (self::$num_drivers == 1) {
            $db = self::get();
            return call_user_func_array(array($db, $name), $arguments);
        } else {
            throw new \Exception("Please select database connection !");
        }
    }
    
    public static function get($name = null) {
        if ($name == null && self::$num_drivers > 0) {
            return self::$drivers[key(self::$drivers)];
        } elseif (array_key_exists($name, self::$drivers)) {
            return self::$drivers[$name];
        } else {
            throw new \Exception("Database connection `{$name}` is not exists !");
        }
    }
    
}