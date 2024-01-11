<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_admin_charge_model
{
    protected $database;
    var $table = "admin_charge";
    var $is_success = true;

    function __construct()
    {
        
        $this->database = Query_builder::getInstance();
    }

    public function append_admin_charge($admin_charge)
    {

        $data_to_insert = array(
            'admin_charge' => $admin_charge,
            'domain' => 'binhusenstore'
        );

        $this->database->insert($this->table, $data_to_insert);

        if($this->database->is_error === null) {
    
            return true;
        }   
            
        $this->is_success = $this->database->is_error;

    }

    public function retrieve_admin_charge()
    {

        $result = $this->database->select_where($this->table, 'domain', 'binhusenstore')->fetchAll(PDO::FETCH_ASSOC);
        
        if($this->database->is_error === null) {

            return $result['0']['admin_charge'];
        }
        
        $this->is_success = $this->database->is_error;
        return 0;
        
    }

    public function update_binhusenstore_admin_charge($admin_charge)
    {

        $result = $this->database->update($this->table, array('admin_charge' => $admin_charge), 'domain', 'binhusenstore');

        if($this->database->is_error === null) {
            
            return $result;
        } 

        $this->is_success = $this->database->is_error;

    }
}
