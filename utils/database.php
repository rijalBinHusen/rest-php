<?php

require_once(__DIR__ . "/piece/addslahes_array.php");

class Query_builder
{

    private static $instance;
    protected $db;
    public $is_error = null;

    function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            $host = MYSQL_HOST;
            $db_name = MYSQL_DB_NAME;
            $db_user = MYSQL_DB_USER;
            $db_password = MYSQL_DB_PASSWORD;

            $connection = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance = new static($connection);
        }

        return self::$instance;
    }

    // merupakan fungsi untuk melihat tabel dari database ( select *from )
    function select_from($tabel, $column_str = "*", $order_by = "", $is_desc = false, $limiter = 0, $where = false, $criteria = false)
    {
        $is_limiter_oke = is_numeric($limiter) && $limiter > 0;
        $is_order_by_oke = !is_null($order_by) && !empty($order_by) && is_string($order_by) && strlen($order_by) <= 40;

        try {
            $sql = "SELECT $column_str FROM $tabel";

            if ($where && $criteria) $sql = $sql .= " WHERE $where = :$where ";
            if ($is_order_by_oke) $sql = $sql . " ORDER BY " . $order_by;
            if ($is_order_by_oke && $is_desc) $sql = $sql . " DESC ";
            if ($is_limiter_oke) $sql = $sql . " LIMIT " . $limiter;

            $result = $this->db->prepare($sql);
            if ($where && $criteria) $result->bindValue($where, $criteria);
            $result->execute();
            return $result;
        } catch (PDOException $e) {

            $this->is_error = $e;
        }
    }

    // merupakan fungsi untuk melihat data table dari database berdasarkan id
    function select_where($tabel, $where, $id, $order_by = "", $is_desc = false)
    {
        $is_order_by_oke = !is_null($order_by) && !empty($order_by) && is_string($order_by) && strlen($order_by) <= 40;
        try {

            $query = "SELECT * FROM $tabel WHERE $where = :$where";

            if ($is_order_by_oke) $query .= " ORDER BY " . $order_by;
            if ($is_order_by_oke && $is_desc) $query = $query . " DESC ";

            $row = $this->db->prepare($query);
            $row->bindValue($where, $id);
            $row->execute();
            return $row;
        } catch (PDOException $e) {

            $this->is_error = $e;
        }
    }

    function select_where_s($table, $where_s, $order_by = "", $is_desc = false, $limit = 0)
    {
        $setPart = array();

        foreach ($where_s as $key => $value) {
            $setPart[] = $key . "=:" . $key;
        }

        $sql = "SELECT * FROM $table WHERE "  . implode(" AND ", $setPart);

        $is_order_by_oke = !is_null($order_by) && !empty($order_by) && is_string($order_by) && strlen($order_by) <= 40;
        if ($is_order_by_oke) $sql = $sql . " ORDER BY :order_by";
        if ($is_order_by_oke && $is_desc) $sql = $sql . " DESC ";
        if ($limit > 0) $sql = $sql . " LIMIT :limit";

        $row = $this->db->prepare($sql);
        if ($is_order_by_oke) $row->bindValue('order_by', $order_by, PDO::PARAM_STR);
        if ($limit > 0) $row->bindValue('limit', (int)$limit, PDO::PARAM_INT);

        foreach ($where_s as $param => $val) {
            $row->bindValue($param, $val);
        }

        $row->execute();
        return $row;
    }

    // merupakan fungsi untuk tambah data
    function insert($tabel, $paramsArr)
    {
        $key = array_keys($paramsArr);
        $val = adslashes_array(array_values($paramsArr));

        $query = "INSERT INTO $tabel (" . implode(', ', $key) . ") "
            . "VALUES ('" . implode("', '", $val) . "')";

        try {

            $row = $this->db->prepare($query);
            $row->execute();
            return $this->db->lastInsertId();
        } catch (PDOException $e) {

            $this->is_error = $e;
        }
    }

    // merupakan fungsi edit data
    function update($tabel, $data, $where, $id)
    {
        $setPart = array();

        foreach ($data as $key => $value) {
            $setPart[] = $key . "=:" . $key;
        }

        try {

            $sql = "UPDATE $tabel SET " . implode(', ', $setPart) . " WHERE $where = :id";
            $row = $this->db->prepare($sql);
            //Bind our values.

            $row->bindValue(':id', $id); // where

            foreach ($data as $param => $val) {
                $row->bindValue($param, $val);
            }

            $row->execute();

            return $row->rowCount();
        } catch (PDOException $e) {

            $this->is_error = $e;
        }
    }

    function update_where_s($tabel, $data, $where_s)
    {
        $setPart = array();

        foreach ($data as $key => $value) {
            $setPart[] = $key . "=:" . $key;
        }

        $where_part = array();
        foreach ($where_s as $key => $value) {
            $where_part[] = $key . "=:" . $key;
        }

        try {

            $sql = "UPDATE $tabel SET " . implode(', ', $setPart) . " WHERE " . implode(" AND ", $where_part);
            $row = $this->db->prepare($sql);
            //Bind our values.

            // $row->bindValue(':id', $id); // where
            foreach ($where_s as $param => $val) {
                $row->bindValue($param, $val);
            }

            foreach ($data as $param => $val) {
                $row->bindValue($param, $val);
            }

            $row->execute();

            return $row->rowCount();
        } catch (PDOException $e) {

            $this->is_error = $e;
        }
    }

    // merupakan fungsi untuk hapus data
    function delete($tabel, $where, $id)
    {
        try {

            $sql = "DELETE FROM $tabel WHERE $where = ?";
            $row = $this->db->prepare($sql);
            $row->execute(array($id));
            return $row->rowCount();
        } catch (PDOException $e) {

            $this->is_error = $e;
        }
    }

    function sqlQuery($your_query)
    {
        // return $this->db->query($your_query);
        $stmt = $this->db->prepare($your_query);
        $stmt->execute();
        return $stmt;
    }

    function custom_query_return_prepare($your_query)
    {
        return $this->db->prepare($your_query);
    }

    function getMaxId($tableName)
    {
        $last_id = $this->db->lastInsertId();

        if ($last_id == 0) {
            $sql = "SELECT MAX(id) as id FROM $tableName";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            $row = $stmt->fetch();
            $last_id = $row['id'];
        }

        return $last_id;
    }

    function is_id_exists($table_name, $id)
    {

        try {

            $row = $this->db->prepare("SELECT * FROM $table_name WHERE id = ?");
            $row->execute(array($id));
            return $row === 1;
        } catch (PDOException $e) {

            $this->is_error = $e;
            return false;
        }
    }

    function select_where_match_full_text($table, $column_str, $where, $against, $order_by, $is_desc, $limit)
    {
        try {

            $query = "SELECT $column_str FROM $table";

            if ($where && $against) $query = $query . " WHERE MATCH($where) AGAINST (:against IN NATURAL LANGUAGE MODE)";

            if ($order_by) {
                $query = $query . " ORDER BY $order_by";

                if ($is_desc) $query = $query . " DESC";
            }

            if ($limit) $query = $query . " LIMIT :limit";

            $row = $this->db->prepare($query);

            if ($where && $against) $row->bindValue('against', $against, PDO::PARAM_STR);
            if ($limit) $row->bindValue('limit', (int)$limit, PDO::PARAM_INT);

            $row->execute();
            return $row;
        } catch (PDOException $e) {

            $this->is_error = $e;
            return false;
        }
    }

    function select_where_like($table_name, $where, $like)
    {

        try {

            $row = $this->db->prepare("SELECT * FROM $table_name WHERE :table_column LIKE :criteria ");
            $row->bindValue('table_column', $where);
            $row->bindValue('criteria', $like);
            $row->execute();
            return $row;
        } catch (PDOException $e) {

            $this->is_error = $e;
            return false;
        }
    }

    function __destruct()
    {
        self::$instance = null;
        $this->db = null;
    }
}
