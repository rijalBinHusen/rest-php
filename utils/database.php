<?php 

require_once(__DIR__ ."/../vendor/autoload.php");

// Call dotenv package
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
// load dotenv package
$dotenv->load();


class Query_builder {

    protected $db;
    public $is_error = null;

    function __construct(){
        // get database configuration from dotenv file
        $database = getenv('DATABASE');
        // get username database from dotenv file
        $username = getenv('DATABASE_USER');
        // get password database from dotenv file
        $password = getenv('DATABASE_PASSWORD');

        try {

            $this->db = new PDO($database, $username, $password);
            // set the PDO error mode to exception
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // set value to connection status
            $this->is_error = null;

        } catch (PDOException $e) {
            // set value to connection status
            $this->is_error = "Connection failed: " . $e->getMessage();
        }
    }

    // merupakan fungsi untuk melihat tabel dari database ( select *from )
    function select_from($tabel)
    {
        try {

            $result = $this->db->query("SELECT * FROM $tabel");
            return $result;

        } catch (PDOException $e) {

            $this->is_error = $e;
            
        }
    }

    // merupakan fungsi untuk melihat data table dari database berdasarkan id
    function select_where($tabel,$where,$id)
    {
        try {

            $row = $this->db->prepare("SELECT * FROM $tabel WHERE $where = ?");
            $row->execute(array($id));
            return $row;

        } catch (PDOException $e) {

            $this->is_error = $e;
            
        }
    }

    // merupakan fungsi untuk tambah data
    function insert($tabel,$paramsArr)
    {
        $key = array_keys($paramsArr);
        $val = array_values($paramsArr);

        $query = "INSERT INTO $tabel (" . implode(', ', $key) . ") "
            . "VALUES ('" . implode("', '", $val) . "')";

        try {   

            $row = $this->db->prepare($query);
            $row ->execute();
            return $this->db->lastInsertId();

        } catch (PDOException $e) {

            $this->is_error = $e;
            
        }
    }

    // merupakan fungsi edit data
    function update($tabel, $data, $where, $id)
    {
        $setPart = array();

        foreach ($data as $key => $value)
        {
            $setPart[] = $key."=:".$key;
        }

        try {

            $sql = "UPDATE $tabel SET ".implode(', ', $setPart)." WHERE $where = :id";
            $row = $this->db->prepare($sql);
            //Bind our values.
            
            $row ->bindValue(':id',$id); // where
            
            foreach($data as $param => $val)
            {
                $row ->bindValue($param, $val);
            }

            $row->execute();

            return $row->rowCount();

        }  catch (PDOException $e) {

            $this->is_error = $e;
            
        }
    }
    
    // merupakan fungsi untuk hapus data
    function delete($tabel,$where,$id)
    {
        try {

            $sql = "DELETE FROM $tabel WHERE $where = ?";
            $row = $this->db->prepare($sql);
            $row ->execute(array($id));
            return $row->rowCount();

        }  catch (PDOException $e) {

            $this->is_error = $e;
            
        }
    }

    function sqlQuery($query) {
        $row = $this->db->prepare($query);
        $row->execute();
        return $row->fetch();
    }

}

?>