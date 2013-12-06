<?php
namespace Database\Adapter;
defined('DS') or die();

final class Mysql extends \Database\Sql {
    
    public function __construct($server, $username, $password, $db_name, $options = null) {
        if (!in_array('mysql', \PDO::getAvailableDrivers())) {
            throw new \Exception("Mysql driver is not found !");
        }
        
        parent::__construct($server, $username, $password, $db_name, $options);
        
        $this->options[\PDO::MYSQL_ATTR_INIT_COMMAND] = 'SET NAMES utf8';
    }

    public function getDsn() {
        $dsn = sprintf("mysql:host=%s;dbname=%s", $this->server, $this->db_name);
        return $dsn;
    }

}