<?php
namespace Database;
defined('DS') or die();

abstract class Sql {

    protected $pdo = null;
    protected $server = null;
    protected $username = null;
    protected $password = null;
    protected $db_name = null;
    protected $options = null;

    public function __construct($server, $username, $password, $db_name, $options = null) {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db_name = $db_name;

        if ($options == null) {
            $options = array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            );
        }

        $this->options = $options;
    }

    public function connect() {
        if ($this->pdo !== null) {
            return $this->pdo;
        }

        try {
            $dsn = $this->getDsn();
            $this->pdo = new \PDO($dsn, $this->username, $this->password, $this->options);
            return $this->pdo;
        } catch (\Exception $e) {
            __error__($e->getMessage());
        }
    }

    abstract function getDsn();

    function disconnect() {
        $this->pdo = null;
    }

    public function queryAll($sql) {
        $db = $this->connect();
        $args = func_get_args();
        
        $stmt = $db->prepare($sql);
        if (count($args) > 1) {
            array_shift($args);

            $i = 0;
            foreach ($args as &$value) {
                $stmt->bindValue( ++$i, $value);
            }
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll();
        }

        return false;
    }

    public function queryRow($sql) {
        $db = $this->connect();
        $args = func_get_args();

        $stmt = $db->prepare($sql);
        if (count($args) > 1) {
            array_shift($args);

            $i = 0;
            foreach ($args as &$value) {
                $stmt->bindValue( ++$i, $value);
            }
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }

        return false;
    }

    public function queryColumn($query) {
        $db = $this->connect();
        $args = func_get_args();

        $stmt = $db->prepare($query);
        if (count($args) > 1) {
            array_shift($args);

            $i = 0;
            foreach ($args as &$value) {
                $stmt->bindValue( ++$i, $value);
            }
        }

        $stmt->execute();

        if ($stmt->rowCount()) {
            return $stmt->fetchColumn();
        }

        return 0;
    }

    public function queryAffected($query) {
        $db = $this->connect();
        $args = func_get_args();

        $stmt = $db->prepare($query);
        if (count($args) > 1) {
            array_shift($args);

            $i = 0;
            foreach ($args as &$value) {
                $stmt->bindValue( ++$i, $value);
            }
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function insertData($table, $data) {
        if (count($data) == 0) {
            return 0;
        }

        $db = $this->connect();

        $array_question_marks = array_fill(0, count($data), '?');
        $query = 'INSERT INTO `' . $table . '` (`' . implode('`, `', array_keys($data)) . '`) VALUES ('
                . implode(', ', $array_question_marks) . ')';

        $stmt = $db->prepare($query);
        $i = 0;
        $values = array_values($data);

        foreach ($values as &$value) {
            $stmt->bindValue( ++$i, $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function updateData($table, $data, $where_stmt = NULL) {
        if (count($data) == 0) {
            return 0;
        }

        $db = $this->connect();
        $set_array = array();

        foreach (array_keys($data) as $key) {
            array_push($set_array, '`' . $key . '` = ?');
        }

        $query = 'UPDATE `' . $table . '` SET ' . implode(', ', $set_array) .
                ($where_stmt ? ' WHERE ' . $where_stmt : '');

        $stmt = $db->prepare($query);
        $i = 0;
        $values = array_values($data);
        $where_data = ($where_stmt != NULL) ? array_slice(func_get_args(), 3) : array();

        foreach ($values as &$value) {
            $stmt->bindValue( ++$i, $value);
        }
        foreach ($where_data as &$value) {
            $stmt->bindValue( ++$i, $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deleteData($table, $where_stmt = NULL) {
        $query = 'DELETE FROM `' . $table . '`' . ($where_stmt ? ' WHERE ' . $where_stmt : '');

        $db = $this->connect();

        $stmt = $db->prepare($query);
        $i = 0;
        $where_data = ($where_stmt != NULL) ? array_slice(func_get_args(), 2) : array();

        foreach ($where_data as &$value) {
            $stmt->bindValue( ++$i, $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }

    public function lastInsertId() {
        $db = $this->connect();
        return $db->lastInsertId();
    }
    
    public function __call($name, $arguments) {
        try {
            return call_user_func_array(array($this->pdo, $name), $arguments);
        } catch (\Exception $e) {
            __error__($e->getMesssage());
        }
    }

}
