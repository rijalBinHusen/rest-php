<?php 

require_once(__DIR__ . "/piece/addslahes_array.php");

class Query_builder {

    private static $instance;
    protected $db;
    public $is_error = null;

    function __construct(PDO $connection){
        $this->db = $connection;            
    }

    public static function getInstance() {
        if (self::$instance === null) {
            $connection = new PDO('mysql:host=localhost;dbname=myreport', 'root', '');
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance = new static($connection);
        }

        return self::$instance;
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
        $val = adslashes_array(array_values($paramsArr));

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

    function sqlQuery($your_query) {
        return $this->db->query($your_query);
    }
}

?>