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
            $sql = "INSERT INTO " . $table . $columns . " VALUES " . $values . " )";
            // use exec() because no results are returned
            $this->conn->exec($sql);
            return "New record created successfully";
        } catch (PDOException $e) {
            $this->log_error("append", $table, $e->getMessage());
            return false;
        }
    }
    public function deleteData($table, $column, $criteria)
    {
        try {
            // sql to delete a record
            $sql = "DELETE FROM " . $table . " WHERE " . $column . "=" . $criteria;

            // use exec() because no results are returned
            $this->conn->exec($sql);
            return "Record deleted successfully";
        } catch (PDOException $e) {
            $this->log_error("append", $table, $e->getMessage());
            return false;
        }
    }
    public function findDataByColumnCriteria($table, $allColumns, $columnToSearch, $criteria)
    {
        // initiate array
        $result = array();
        // we are gonna split string as array, so we can loop it bruh
        $arrOfColumns = explode(", ", $allColumns);
        try {
            $query = "SELECT $allColumns from $table WHERE $columnToSearch = $criteria";
            $query = $this->conn->query($query);
            // set the resulting array to associative
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
            $this->log_error("append", $table, $e->getMessage());
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
            $this->log_error("append", $table, $e->getMessage());
            return false;
        }
    }
    public function updateDataByCriteria($table, $keyValueToUpdate, $columnCriteria, $criteria)
    {   
        try {

            $sql = "UPDATE " . $table . " SET " . $keyValueToUpdate . " WHERE " . $columnCriteria . "=" . $criteria;
            //// Prepare statement
            $stmt = $this->conn->prepare($sql);
            
            // execute the query
            $stmt->execute();
            
            // echo a message to say the UPDATE succeeded
            return $stmt->rowCount() . " records UPDATED successfully";
            // return $sql;
        } catch (PDOException $e) {
            $this->log_error("append", $table, $e->getMessage());
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
        $querySql = "SELECT * FROM $table ORDER BY id DESC LIMIT 1";
        $query = $this->conn->query($querySql);
        $row = $query->fetch();
        return $row;
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
    public function log_error($operation, $name_table, $message) {
        $this->writeData("error_log", "operation, name_table, message_error", $operation, $name_table, $message);
    }
    function __destruct()
    {
        $this->conn  = null;
    }
}
