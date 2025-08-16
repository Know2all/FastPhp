<?php
require_once("Config.php");
class Manager {
    protected static $conn;
    public $query;
    public $affected_rows;
    public $error;

    public static function setConnection($conn) {
        self::$conn = $conn;
    }

    protected function escapeString($string) {
        return mysqli_real_escape_string(self::$conn, $string);
    }

    function create($tbl, $data) {
        $fields = implode(",", array_keys($data));
        $data_escaped = array_map(fn($v) => $this->escapeString($v), $data);
        $values = "'" . implode("','", $data_escaped) . "'";
        $this->query = "INSERT INTO {$tbl}({$fields}) VALUES({$values})";
        
        try {
            $result = mysqli_query(self::$conn, $this->query);
            if (!$result) throw new Exception(mysqli_error(self::$conn));
            $this->affected_rows = mysqli_affected_rows(self::$conn);
            return $this->affected_rows > 0;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    function fetchAll($tbl, $condition = "") {
        $this->query = "SELECT * FROM {$tbl}";
        if ($condition) $this->query .= " WHERE $condition";
        try {
            $result = mysqli_query(self::$conn, $this->query);
            if (!$result) throw new Exception(mysqli_error(self::$conn));
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $this->affected_rows = count($rows);
            return $rows;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    function fetchOne($tbl, $condition = "") {
        $this->query = "SELECT * FROM {$tbl}";
        if ($condition) $this->query .= " WHERE $condition LIMIT 1";
        try {
            $result = mysqli_query(self::$conn, $this->query);
            if (!$result) throw new Exception(mysqli_error(self::$conn));
            $row = mysqli_fetch_assoc($result);
            $this->affected_rows = $row ? 1 : 0;
            return $row ?: [];
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    function update($tbl, $data, $condition = "") {
        $update_fields = [];
        foreach ($data as $key => $value) {
            $escaped = $this->escapeString($value);
            $update_fields[] = "{$key} = '{$escaped}'";
        }
        $set_clause = implode(", ", $update_fields);
        $this->query = "UPDATE {$tbl} SET {$set_clause}" . ($condition ? " WHERE {$condition}" : "");
        
        try {
            $result = mysqli_query(self::$conn, $this->query);
            if (!$result) throw new Exception(mysqli_error(self::$conn));
            $this->affected_rows = mysqli_affected_rows(self::$conn);
            return $this->affected_rows > 0;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    function remove($tbl, $condition = "") {
        $this->query = "DELETE FROM {$tbl}" . ($condition ? " WHERE {$condition}" : "");
        try {
            $result = mysqli_query(self::$conn, $this->query);
            if (!$result) throw new Exception(mysqli_error(self::$conn));
            $this->affected_rows = mysqli_affected_rows(self::$conn);
            return $this->affected_rows > 0;
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }
}

class Model extends Manager {
    protected $table;
    protected $primaryKey = "id";
    protected $columns = [];

    function __construct($table) {
        $this->table = $table;
        $this->loadColumns();
    }

    protected function loadColumns() {
        $sql = "SHOW COLUMNS FROM {$this->table}";
        $result = mysqli_query(self::$conn, $sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $this->columns[] = $row['Field'];
            $this->{$row['Field']} = null;
        }
    }

    function fill($data) {
        foreach ($this->columns as $col) {
            if (isset($data[$col])) {
                $this->{$col} = $data[$col];
            }
        }
    }

    function save() {
        $data = [];
        foreach ($this->columns as $col) {
            if ($col == $this->primaryKey) continue;
            $data[$col] = $this->{$col};
        }

        if (!empty($this->{$this->primaryKey})) {
            return $this->update(
                $this->table, 
                $data, 
                "{$this->primaryKey}='{$this->{$this->primaryKey}}'"
            );
        } else {
            return $this->create($this->table, $data);
        }
    }

    function delete() {
        if (!empty($this->{$this->primaryKey})) {
            return $this->remove($this->table, "{$this->primaryKey}='{$this->{$this->primaryKey}}'");
        }
        return false;
    }

    static function find($id, $primaryKey = "id") {
        $instance = new static();
        $row = $instance->fetchOne($instance->table, "{$primaryKey}='{$id}'");
        if ($row) $instance->fill($row);
        return $instance;
    }

    static function where($conditions = []) {
        $instance = new static();
        $where = self::buildWhere($conditions);
        $rows = $instance->fetchAll($instance->table, $where);
        
        $results = [];
        foreach ($rows as $row) {
            $obj = new static();
            $obj->fill($row);
            $results[] = $obj;
        }
        return $results;
    }

    static function get($conditions = []) {
        $instance = new static();
        $where = self::buildWhere($conditions);
        $row = $instance->fetchOne($instance->table, $where);
        if ($row) {
            $instance->fill($row);
            return $instance;
        }
        return null;
    }

    static function all() {
        return self::where([]);
    }
    protected static function buildWhere($conditions) {
        if (empty($conditions)) return "";
        $clauses = [];
        foreach ($conditions as $key => $value) {
            $escaped = mysqli_real_escape_string(self::$conn, $value);
            $clauses[] = "{$key}='{$escaped}'";
        }
        return implode(" AND ", $clauses);
    }
}


?>
