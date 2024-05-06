<?php 

class Manager{

    public $conn;
    public $query;
    public $affected_rows;

    function __construct($conn){
        $this->conn = $conn;
    }

    function escapeString($string) {
        $escaped_string = mysqli_real_escape_string($this->conn, $string);
        return $escaped_string;
    }
    
    function create($tbl,$data){
        $fields = implode(",", array_keys($data));
        $data_escaped = array_map(function($value){
            return $this->escapeString($value);
        }, $data);
        $values = "'".implode("','",$data_escaped )."'";
        $this->query = "INSERT INTO {$tbl}({$fields}) VALUES({$values})";
        try {
            $result = mysqli_query($this->conn,$this->query);
            if (!$result) {
                throw new Exception(mysqli_error($this->conn));
            }
            $this->affected_rows = mysqli_affected_rows($this->conn);
            if($this->affected_rows > 0){
                return true;
            }
        }catch (Exception $e) {
            echo "Exception occurs: " . $e->getMessage();
            return false;
        }
    }

    function fetchAll($tbl, $condition = ""){
        $this->query = "SELECT * FROM {$tbl} ";
        if(!empty($condition)){
            $this->query .= " WHERE $condition";
        }
        try {
            $result = mysqli_query($this->conn,$this->query);
            if (!$result) {
                throw new Exception(mysqli_error($this->conn));
            }
            $this->affected_rows = mysqli_affected_rows($this->conn);
            if($this->affected_rows > 0){
                $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
                return $rows;
            }
            return array();
        } catch (Exception $e) {
            echo "Exception occurs: " . $e->getMessage();
            return false;
        }
    }

    function fetchOne($tbl, $condition = ""){
        $this->query = "SELECT * FROM {$tbl} ";
        if(!empty($condition)){
            $this->query .= " WHERE {$condition}";
        }
        try {
            $result = mysqli_query($this->conn,$this->query);
            if (!$result) {
                throw new Exception(mysqli_error($this->conn));
            }
            $this->affected_rows = mysqli_affected_rows($this->conn);
            if($this->affected_rows > 0){
                $rows = mysqli_fetch_assoc($result);
                return $rows;
            }
            return array();
        } catch (Exception $e) {
            echo "Exception occurs: " . $e->getMessage();
            return false;
        }
    }

    function update($tbl, $data, $condition = "") {
        $update_fields = array();
        foreach ($data as $key => $value) {
            $escaped_value = $this->escapeString($value);
            $update_fields[] = "{$key} = '{$escaped_value}'";
        }
        $set_clause = implode(", ", $update_fields);
    
        $this->query = "UPDATE {$tbl} SET {$set_clause}";
    
        if (!empty($condition)) {
            $this->query .= " WHERE {$condition}";
        }
    
        try {
            if (mysqli_query($this->conn, $this->query)) {
                $this->affected_rows = mysqli_affected_rows($this->conn);
                if($this->affected_rows > 0){
                    return true;
                }
            } else {
                throw new Exception(mysqli_error($this->conn));
            }
        } catch (Exception $e) {
            echo "Exception occurs: " . $e->getMessage();
            return false;
        }
    }

    function delete($tbl,$condition=""){
        $this->query = "DELETE FROM {$tbl}";
        if(!empty($condition)){
            $this->query .=" WHERE {$condition}";
        }
        try{
            if (mysqli_query($this->conn, $this->query)) {
                $this->affected_rows = mysqli_affected_rows($this->conn);
                if($this->affected_rows > 0){
                    return true;
                }
            } else {
                throw new Exception(mysqli_error($this->conn));
            }
        } catch (Exception $e) {
            echo "Exception occurs: " . $e->getMessage();
            return false;
        }
    }
    
    function Query($q){
        $this->query = $q;
        try{
            $result = mysqli_query($this->conn,$this->query);
            if(!$result){
                throw new Exception(mysqli_error($this->conn));
            }
            $this->affected_rows = mysqli_affected_rows($this->conn);
            if($this->affected_rows > 0){
                return $result;
            }
        }catch (Exception $e) {
            echo "Exception occurs: " . $e->getMessage();
            return false;
        }
    }
}

?>