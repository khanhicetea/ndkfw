<?php
namespace Database\Adapter;
defined('DS') or die();

final class Mongo extends \Database\NoSql {
    private $client = null;
    private $db = null;


    public function connect() {
        $this->client = new \MongoClient($this->connection_string, $this->options);
    }
    
    public function selectDb($db_name) {
        if ($this->client == null) {
            $this->connect();
        }
        
        $this->db = $this->client->selectDB($db_name);
    }
    
    public function getClient() {
        return $this->client;
    }
    
    public function getDb() {
        return $this->db;
    }

    public function disconnect() {
        $this->client = null;
    }

}