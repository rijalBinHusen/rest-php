<?php
// Call dotenv package
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
// load dotenv package
$dotenv->load();

class sqldatabase
{
    // variable for database connection
    public $conn;
    // variable for connection status
    public $connection_status;
    // this function will automatically called when the class called e.g new sqldatbase()
    public function __construct()
    {
        // get database configuration from dotenv file
        $database = getenv('DATABASE');
        // get username database from dotenv file
        $username = getenv('DATABASE_USER');
        // get password database from dotenv file
        $password = getenv('DATABASE_PASSWORD');
        try {
            $this->conn = new PDO($database, $username, $password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // set value to connection status
            $this->connection_status = "Connected successfully";
        } catch (PDOException $e) {
            // set value to connection status
            $this->connection_status = "Connection failed: " . $e->getMessage();
        }
    }
    public function writeData($table, $columns, $values)
    {
        try {

            $sql = "INSERT INTO  $table ( $columns ) VALUES ( $values  )";
            // Prepare statement
            $stmt = $this->conn->prepare($sql);
            // execute the query
            $stmt->execute();
            // return a message to say the create succeeded
            return true;
            // return $sql;
        } catch (PDOException $e) {
            $this->log_error("create", $table, $e->getMessage());
            return false;
        }
    }
    public function deleteData($table, $column, $criteria)
    {
        try {
            // sql to delete a record
            $sql = "DELETE FROM $table WHERE $column=$criteria";
            // Prepare statement
            $stmt = $this->conn->prepare($sql);
            // execute the query
            $stmt->execute();
            // return message
            return "Record deleted successfully";
            // catching an error
        } catch (PDOException $e) {
            $this->log_error("delete", $table, $e->getMessage());
            return false;
        }
    }
    public function findDataByColumnCriteria($table, $allColumns, $columnToSearch, $criteria)
    {
        // initiate array
        $result = null;
        // we are gonna split string as array, so we can loop it bruh
        $replace_all_space_from_columns = trim(preg_replace('/\s\s+/', ' ', $allColumns));
        $arrOfColumns = explode(", ", $replace_all_space_from_columns);
        try {
            $sql = "SELECT $allColumns from $table WHERE $columnToSearch = $criteria";
            $query = $this->conn->query($sql);
            // set the resulting array to associative
            while ($row = $query->fetch()) {
                $tempResult = array();
                // iterate the columns
                foreach ($arrOfColumns as $column) {
                    // tempResult { tempResult: row }
                    $tempResult[$column] = $row[$column];
                }
                // push to result
                $result = $tempResult;
            }
            return $result;
        } catch (PDOException $e) {
            $this->log_error("find data by criteria", $table, $e->getMessage());
            return false;
        }
    }
    public function getData($columns, $table, $totalRow = 10)
    {
        // initiate array
        $result = array();
        // we are gonna split string as array, so we can loop it bruh
        $arrOfColumns = explode(", ", $columns);
        // the query
        try {
            $querySql = 'SELECT ' . $columns . ' from ' . $table . ' LIMIT ' . $totalRow;
            $query = $this->conn->query($querySql);
            while ($row = $query->fetch()) {
                $tempResult = array();
                // iterate the columns
                foreach ($arrOfColumns as $column) {
                    // tempResult { tempResult: row }
                    $tempResult[$column] = $row[$column];
                }
                // push to result
                array_push($result, $tempResult);
            }
            return $result;
        } catch (PDOException $e) {
            $this->log_error("get data", $table, $e->getMessage());
            return false;
        }
    }
    public function updateDataByCriteria($table, $keyValueToUpdate, $columnCriteria, $criteria)
    {
        try {

            $sql = "UPDATE " . $table . " SET " . $keyValueToUpdate . " WHERE " . $columnCriteria . "=" . $criteria;
            // Prepare statement
            $stmt = $this->conn->prepare($sql);

            // execute the query
            $stmt->execute();

            // echo a message to say the UPDATE succeeded
            return $stmt->rowCount() . " records UPDATED successfully";
            // return $sql;
        } catch (PDOException $e) {
            $this->log_error("update", $table, $e->getMessage());
            return false;
        }
    }
    public function status()
    {
        // return value when the function called
        echo $this->connection_status;
    }
    public function getLastId($table)
    {
        try {

            $sql = "SELECT * FROM $table ORDER BY id DESC LIMIT 1";
            //// Prepare statement
            $stmt = $this->conn->prepare($sql);

            // execute the query
            $stmt->execute();

            // echo a message to say the UPDATE succeeded
            $row = $stmt->fetch();
            // return $sql;
            return $row;
        } catch (PDOException $e) {
            $this->log_error("get last id", $table, $e->getMessage());
            return false;
        }
    }
    public function getAllData($columns, $table)
    {
        // initiate array
        $result = array();
        // we are gonna split string as array, so we can loop it bruh
        $arrOfColumns = explode(", ", $columns);
        // the query
        $querySql = 'SELECT ' . $columns . ' from ' . $table;
        // execute the query
        $query = $this->conn->query($querySql);
        // iterate the result query
        while ($row = $query->fetch()) {
            $tempResult = array();
            // iterate the columns
            foreach ($arrOfColumns as $column) {
                // tempResult { tempResult: row }
                $tempResult[$column] = $row[$column];
            }
            // push to result
            array_push($result, $tempResult);
        }
        return $result;
    }
    public function log_error($operation, $name_table, $message)
    {
        $this->writeData("error_log", "operation, name_table, message_error", "'$operation', '$name_table', '$message'");
    }
    public function get_data_by_where_query($columns, $table, $your_query)
    {
        // your query = column_name BETWEEN value1 AND value2;
        // your query = column_name >= 10 AND column_name <= 20;
        // your query = column_name NOT BETWEEN 100 AND 150;
        // initiate array
        $result = array();
        // we are gonna split string as array, so we can loop it bruh
        $arrOfColumns = explode(", ", $columns);
        // the query
        try {
            $querySql = "SELECT $columns from $table WHERE $your_query";
            $query = $this->conn->query($querySql);
            while ($row = $query->fetch()) {
                $tempResult = array();
                // iterate the columns
                foreach ($arrOfColumns as $column) {
                    // tempResult { tempResult: row }
                    $tempResult[$column] = $row[$column];
                }
                // push to result
                array_push($result, $tempResult);
            }
            return $result;
        } catch (PDOException $e) {
            $this->log_error("get data", $table, $e->getMessage());
            return false;
        }
    }
    function __destruct()
    {
        $this->conn  = null;
    }
}
