<?php

require_once "config.php";

abstract class baseDAO
{
    private $__connection;

    public function __construct()
    {
        $this->__connectToDB(DB_USER, DB_PASS, DB_HOST, DB_DATABASE);
    }

    private function __connectToDB($user, $pass, $host, $database)
    {
        $this->__connection = mysqli_connect($host, $user, $pass, $database);
    }

    public function fetch($where)
    {
        $sql = "SELECT * FROM {$this->_tableName} WHERE " . $where;
        $results = mysqli_query($this->__connection, $sql);

        while ($result = mysqli_fetch_array($results)) {
            $rows[] = $result;
        }

        return empty($rows) ? null : $rows;
    }

    public function update($keyedArray)
    {
        $sql = "UPDATE {$this->_tableName} SET ";

        $updates = array();
        foreach ($keyedArray as $column => $value) {
            $updates[] = "{$column} = '{$value}'";
        }

        $sql .= implode(',', $updates);
        $sql .= "WHERE {$this->_primaryKey} = '{$keyedArray[$this->_primaryKey]}'";

        mysqli_query($this->__connection, $sql);
    }

    public function insert($insertData)
    {
        $sql = "INSERT INTO {$this->_tableName} ({$this->_fields}) VALUES";

        foreach ($insertData as $key => $value) {
            if($key != 0) {
                $sql .= ",";
            }
            $sql .= "(" . implode(",", $value) . ")";
        }

        mysqli_query($this->__connection, $sql);
    }

}