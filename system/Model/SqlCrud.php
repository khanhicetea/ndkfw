<?php
namespace Model;
defined('DS') or die();

abstract class SqlCrud extends BaseModel {
    protected $table_name = '';
    protected $primary_field = '';
    protected $orders = array();
    protected $rows_per_page = 20;
    
    private function getOrderString() {
        if (empty($this->orders)) {
            return '';
        } else {
            $orders = array();
            foreach ($this->orders as $order) {
                $orders[] = "`{$order['field']}` {$order['type']}";
            }
                
            return ' ORDER BY ' . implode(',', $orders);
        }
    }

    public function getAll() {
        $sql = "SELECT * FROM `{$this->table_name}`" . $this->getOrderString();
        return $this->db->queryAll($sql);
    }

    public function countAll() {
        $sql = "SELECT COUNT(*) as `total` FROM `{$this->table_name}`";
        return intval($this->db->queryColumn($sql));
    }

    public function getByPage($page, $rows_per_page = 0) {
        $page = abs(intval($page));
        $rows_per_page = abs(intval($rows_per_page));

        $rows_per_page > 0 or $rows_per_page = $this->rows_per_page;
        $page > 0 or $page = 1;
        
        $offset = ($page - 1) * $rows_per_page;

        $sql = "SELECT * FROM `{$this->table_name}`" . $this->getOrderString() . " LIMIT {$offset} , {$rows_per_page}";

        return $this->db->queryAll($sql);
    }

    public function getRowBy($value, $field = '') {
        $field != '' or $field = $this->primary_field;
        $sql = "SELECT * FROM `{$this->table_name}`
                    WHERE `{$field}` = ?";

        return $this->db->queryRow($sql, $value);
    }

    public function insert($data) {
        return $this->db->insertData($this->table_name, $data);
    }

    public function update($data, $value, $field = '') {
        $field != '' or $field = $this->primary_field;
        return $this->db->updateData($this->table_name, $data, "`{$field}` = ?", $value);
    }

    public function delete($value, $field = '') {
        $field != '' or $field = $this->primary_field;
        return $this->db->deleteData($this->table_name, "`{$field}` = ?", $value);
    }

    public function changeOrderBy($orders) {
        $this->orders = $orders;
    }
}