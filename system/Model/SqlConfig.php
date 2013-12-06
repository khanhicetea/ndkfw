<?php
namespace Model;
defined('DS') or die();

class SqlConfig extends BaseModel {
    public function getAll($table, $key_field, $value_field) {    
        $sql = "SELECT `{$key_field}`, `{$value_field}` FROM `{$table}`";
        $data = $this->db->queryAll($sql);

        $configs = array();

        foreach ($data as $row) {
            $configs[$row[$key_field]] = $row[$value_field];
        }

        return $configs;
    }

    public function updateAll($table, $key_field, $value_field, $data) {
        $sql = "UPDATE `{$table}` SET `{$value_field}` = ? WHERE `{$key_field}` = ?";
        $this->db->beginTransaction();

        $stmt = $this->db->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->execute(array($value, $key));
        }

        $this->db->commit();
    }
}