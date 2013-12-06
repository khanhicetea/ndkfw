<?php
namespace Database;
defined('DS') or die();

abstract class NoSql {

    protected $connection_string = null;
    protected $options = null;

    public function __construct($connection_string, $options = array()) {
        $this->connection_string = $connection_string;
        $this->options = $options;
    }

    abstract function connect();
    abstract function disconnect();

}
