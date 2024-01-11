<?php
require_once(__DIR__ . '/admin_charge_model.php');

class Binhusenstore_admin_charge
{
    protected $Binhusenstore_admin_charge;
    function __construct()
    {
        $this->Binhusenstore_admin_charge = new Binhusenstore_admin_charge_model();
    }
    
    public function add_admin_charge()
    {
        // request
        $req = Flight::request();
        $admin_charge = $req->data->admin_charge;

        $result = null;

        $is_request_body_not_oke = is_null($admin_charge) || !is_numeric($admin_charge);

        if($is_request_body_not_oke) {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to add admin charge, check the data you sent'
                ), 400
            );
            return;
        }

        // retrieve domain
        $get_domain_admin_charge = $this->Binhusenstore_admin_charge->retrieve_admin_charge();
        $is_domain_exists = $this->Binhusenstore_admin_charge->is_success === true && $get_domain_admin_charge >= 0;
        
        // if domain exists, do update admin charge
        if($is_domain_exists) {
            $this->update_admin_charge();
            return;
        }

        $result = $this->Binhusenstore_admin_charge->append_admin_charge($admin_charge);

        if($this->Binhusenstore_admin_charge->is_success === true) {
        
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Admin charge created'
                ), 201
            );
        } 
        
        else {
            
            Flight::json(
                array(
                    'success'=> false,
                    'message'=> $this->Binhusenstore_admin_charge->is_success
                ), 500
            );
        }
    }
    
    public function get_admin_charge()
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->Binhusenstore_admin_charge->retrieve_admin_charge();

        $is_success = $this->Binhusenstore_admin_charge->is_success;

        if($is_success === true) {
            Flight::json(
                array(
                    'success' => true,
                    'admin_charge' => $result
                )
            );
        }

        else if($is_success !== true) {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $is_success
                ), 500
            );
            return;
        }

        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Admin charge not found'
                ), 404
            );
        }
    }

    public function update_admin_charge()
    {
        // catch the query string request
        $req = Flight::request();
        $admin_charge = $req->data->admin_charge;

        if($admin_charge > 0) {

            $result = $this->Binhusenstore_admin_charge->update_binhusenstore_admin_charge($admin_charge);
    
            $is_success = $this->Binhusenstore_admin_charge->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update admin charge success',
                    )
                );
            }
    
            else if($is_success !== true) {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => $is_success
                    ), 500
                );
                return;
            }
    
            else {
                Flight::json(
                    array(
                        'success' => false,
                        'message' => 'admin charge not found'
                    ), 404
                );
            }
        } 
        
        else {

            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update admin charge, check the data you sent'
                )
            );
        }
    }
}
