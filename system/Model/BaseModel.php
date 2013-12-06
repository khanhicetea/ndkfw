<?php
namespace Model;
use Database\Db;
defined('DS') or die();

abstract class BaseModel {
    
    protected $db = null;
    
    public function __construct($db_connection = null) {
        if ($db_connection == null) {
            $this->db = Db::get();
        } else {
            $this->db = $db_connection;
        }
    }
    
}