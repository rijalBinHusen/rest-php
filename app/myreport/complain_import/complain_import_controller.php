<?php
require_once(__DIR__ . '/complain_import_model.php');

class My_report_complain_import
{
    protected $my_report_complain_import;
    function __construct()
    {
        $this->my_report_complain_import = new My_report_complain_import_model();
    }
    public function get_complains_import()
    { 
        $result = $this->my_report_complain_import->get_complains_import();
        
        $is_exists = count($result) > 0;

        if($this->my_report_complain_import->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->my_report_complain_import->is_success !== true) {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {
            Flight::json( array(
            "success" => false,
            "message" => "Complain import not found"
            ), 404);
        }

    }
    public function add_complain_import()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $customer = $req->data->customer;
        $do_ = $req->data->do_;
        $gudang = $req->data->gudang;
        $item = $req->data->item;
        $kabag = $req->data->kabag;
        $nomor_SJ = $req->data->nomor_SJ;
        $nopol = $req->data->nopol;
        $real_ = $req->data->real_;
        $row_ = $req->data->row_;
        $spv = $req->data->spv;
        $tally = $req->data->tally;
        $tanggal_bongkar = $req->data->tanggal_bongkar;
        $tanggal_info = $req->data->tanggal_info;
        $tanggal_komplain = $req->data->tanggal_komplain;
        $tanggal_SJ = $req->data->tanggal_SJ;
        $type_ = $req->data->type_;

        $result = null;

        $is_request_body_oke = !is_null($do_) && !is_null($gudang) && !is_null($item) && !is_null($kabag) && !is_null($nomor_SJ) && !is_null($nopol) && !is_null($real_) && !is_null($row_) && !is_null($spv) && !is_null($tally) && !is_null($tanggal_bongkar) && !is_null($tanggal_info) && !is_null($tanggal_komplain) && !is_null($tanggal_SJ) && !is_null($type_);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_complain_import->write_complain_import($id, $customer, $do_, $gudang, $item, $kabag, $nomor_SJ, $nopol, $real_, $row_, $spv, $tally, $tanggal_bongkar, $tanggal_info, $tanggal_komplain, $tanggal_SJ, $type_);
            } else {
                // append warehouse
                $result = $this->my_report_complain_import->append_complain_import($customer, $do_, $gudang, $item, $kabag, $nomor_SJ, $nopol, $real_, $row_, $spv, $tally, $tanggal_bongkar, $tanggal_info, $tanggal_komplain, $tanggal_SJ, $type_);
            }

            if($this->my_report_complain_import->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_complain_import->is_success
                    ), 500
                );
                return;
            }
            
            Flight::json(
                array(
                    'success' => true,
                    'id' => $result
                ), 201
            );
            return;
        }

        Flight::json(
            array(
                'success' => false,
                'message' => 'Failed to add complain import, check the data you sent'
            ), 400
        );
    }
    public function get_complain_import_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_complain_import->get_complain_import_by_id($id);

        $is_success = $this->my_report_complain_import->is_success;

        $is_found = count($result) > 0;

        if($is_success === true && $is_found) {
            Flight::json(
                array(
                    'success' => true,
                    'data' => $result
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
                    'message' => 'Complain import not found'
                )
            );
        }
    }

    public function remove_complain_import($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_complain_import->remove_complain_import($id);

        $is_success = $this->my_report_complain_import->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete complain import success',
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
                    'message' => 'Complain import not found'
                )
            );
        }
    }

    public function update_complain_import_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $do_ = $req->data->do_;
        $customer = $req->data->customer;
        $gudang = $req->data->gudang;
        $item = $req->data->item;
        $kabag = $req->data->kabag;
        $nomor_SJ = $req->data->nomor_SJ;
        $nopol = $req->data->nopol;
        $real_ = $req->data->real_;
        $row_ = $req->data->row_;
        $spv = $req->data->spv;
        $tally = $req->data->tally;
        $tanggal_bongkar = $req->data->tanggal_bongkar;
        $tanggal_info = $req->data->tanggal_info;
        $tanggal_komplain = $req->data->tanggal_komplain;
        $tanggal_SJ = $req->data->tanggal_SJ;
        $type_ = $req->data->type_;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional do_
        $valid_do_ = !is_null($do_) && !empty($do_);
        if ($valid_do_) {
            $keyValueToUpdate["do_"] = $do_;
        }

        // conditional $gudang
        $valid_gudang = !is_null($gudang) && !empty($gudang);
        if ($valid_gudang) {
            $keyValueToUpdate["gudang"] = $gudang;
        }

        // conditional $item
        $valid_item = !is_null($item) && !empty($item);
        if ($valid_item) {
            $keyValueToUpdate["item"] = $item;
        }

        // conditional $kabag
        $valid_kabag = !is_null($kabag) && !empty($kabag);
        if ($valid_kabag) {
            $keyValueToUpdate["kabag"] = $kabag;
        }

        // conditional $nomor_SJ
        $valid_nomor_SJ = !is_null($nomor_SJ) && !empty($nomor_SJ);
        if ($valid_nomor_SJ) {
            $keyValueToUpdate["nomor_SJ"] = $nomor_SJ;
        }

        // conditional $item
        $valid_nopol = !is_null($nopol) && !empty($nopol);
        if ($valid_nopol) {
            $keyValueToUpdate["nopol"] = $nopol;
        }

        // conditional $real_
        $valid_real_ = !is_null($real_) && !empty($real_);
        if ($valid_real_) {
            $keyValueToUpdate["real_"] = $real_;
        }

        // conditional $row_
        $valid_row_ = !is_null($row_) && !empty($row_);
        if ($valid_row_) {
            $keyValueToUpdate["row_"] = $row_;
        }

        // conditional $spv
        $valid_spv = !is_null($spv) && !empty($spv);
        if ($valid_spv) {
            $keyValueToUpdate["spv"] = $spv;
        }

        // conditional $tally
        $valid_tally = !is_null($tally) && !empty($tally);
        if ($valid_tally) {
            $keyValueToUpdate["tally"] = $tally;
        }

        // conditional $tanggal_bongkar
        $valid_tanggal_bongkar = !is_null($tanggal_bongkar) && !empty($tanggal_bongkar);
        if ($valid_tanggal_bongkar) {
            $keyValueToUpdate["tanggal_bongkar"] = $tanggal_bongkar;
        }

        // conditional $tanggal_info
        $valid_tanggal_info = !is_null($tanggal_info) && !empty($tanggal_info);
        if ($valid_tanggal_info) {
            $keyValueToUpdate["tanggal_info"] = $tanggal_info;
        }

        // conditional $tanggal_komplain
        $valid_tanggal_komplain = !is_null($tanggal_komplain) && !empty($tanggal_komplain);
        if ($valid_tanggal_komplain) {
            $keyValueToUpdate["tanggal_komplain"] = $tanggal_komplain;
        }

        // conditional $customer
        $valid_customer = !is_null($customer) && !empty($customer);
        if ($valid_customer) {
            $keyValueToUpdate["customer"] = $customer;
        }

        // conditional $tanggal_SJ
        $valid_tanggal_SJ = !is_null($tanggal_SJ) && !empty($tanggal_SJ);
        if ($valid_tanggal_SJ) {
            $keyValueToUpdate["tanggal_SJ"] = $tanggal_SJ;
        }

        // conditional $type_
        $valid_type_ = !is_null($type_) && !empty($type_);
        if ($valid_type_) {
            $keyValueToUpdate["type_"] = $type_;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_complain_import->update_complain_import_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_complain_import->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update complain import success',
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
                        'message' => 'Complain import not found'
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update complain import, check the data you sent'
                )
            );
        }

        
    }
}
