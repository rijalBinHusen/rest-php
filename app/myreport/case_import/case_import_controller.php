<?php
require_once(__DIR__ . '/case_import_model.php');

class My_report_case_import
{
    protected $my_report_case_import;
    function __construct()
    {
        $this->my_report_case_import = new My_report_case_import_model();
    }
    public function get_cases_import()
    { 
        $limit = Flight::request()->query->limit;
        
        $is_it_numeric = is_numeric($limit);

        if($is_it_numeric) {
            $result = $this->my_report_case_import->get_cases_import($limit);
            
            $is_exists = count($result) > 0;

            if($this->my_report_case_import->is_success === true && $is_exists) {
                Flight::json(
                    array(
                        "success" => true,
                        "data" => $result
                        )
                , 200);
            }

            else if ($this->my_report_case_import->is_success !== true) {
                Flight::json( array(
                    "success" => false,
                    "message" => $result
                ), 500);
            }
            
            else {
                Flight::json( array(
                "success" => false,
                "message" => "Case import not found"
                ), 404);
            }
        }

        else {
            Flight::json(array(
                "success" => false,
                "message" => "The query parameter must be number"
                )
            , 400);
        }

    }
    public function add_case_import()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $bagian = $req->data->bagian;
        $divisi = $req->data->divisi;
        $fokus = $req->data->fokus;
        $kabag = $req->data->kabag;
        $karu = $req->data->karu;
        $keterangan1 = $req->data->keterangan1;
        $keterangan2 = $req->data->keterangan2;
        $periode = $req->data->periode;
        $temuan = $req->data->temuan;
        $is_inserted= $req->data->is_inserted;

        $result = null;

        $is_request_body_oke = !is_null($divisi) 
                                && !is_null($fokus) 
                                && !is_null($kabag) 
                                && !is_null($karu) 
                                && !is_null($keterangan1) 
                                && !is_null($keterangan2) 
                                && !is_null($periode) 
                                && !is_null($temuan)
                                && !is_null($is_inserted);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_case_import->write_case_import($id, $bagian, $divisi, $fokus, $kabag, $karu, $keterangan1, $keterangan2, $periode, $temuan, $is_inserted);
            } else {
                // append warehouse
                $result = $this->my_report_case_import->append_case_import($bagian, $divisi, $fokus, $kabag, $karu, $keterangan1, $keterangan2, $periode, $temuan, $is_inserted);
            }

            if($this->my_report_case_import->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_case_import->is_success
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
                'message' => 'Failed to add case import, check the data you sent'
            ), 400
        );
    }
    public function get_case_import_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_case_import->get_case_import_by_id($id);

        $is_success = $this->my_report_case_import->is_success;

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
                    'message' => 'Case import not found'
                ), 404
            );
        }
    }

    public function remove_case_import($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_case_import->remove_case_import($id);

        $is_success = $this->my_report_case_import->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete case import success',
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
                    'message' => 'Case import not found'
                ), 404
            );
        }
    }

    public function update_case_import_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $divisi = $req->data->divisi;
        $bagian = $req->data->bagian;
        $fokus = $req->data->fokus;
        $kabag = $req->data->kabag;
        $karu = $req->data->karu;
        $keterangan1 = $req->data->keterangan1;
        $keterangan2 = $req->data->keterangan2;
        $periode = $req->data->periode;
        $temuan = $req->data->temuan;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional divisi
        $valid_divisi = !is_null($divisi) && !empty($divisi);
        if ($valid_divisi) {
            $keyValueToUpdate["divisi"] = $divisi;
        }

        // conditional $fokus
        $valid_fokus = !is_null($fokus) && !empty($fokus);
        if ($valid_fokus) {
            $keyValueToUpdate["fokus"] = $fokus;
        }

        // conditional $kabag
        $valid_kabag = !is_null($kabag) && !empty($kabag);
        if ($valid_kabag) {
            $keyValueToUpdate["kabag"] = $kabag;
        }

        // conditional $karu
        $valid_karu = !is_null($karu) && !empty($karu);
        if ($valid_karu) {
            $keyValueToUpdate["karu"] = $karu;
        }

        // conditional $keterangan1
        $valid_keterangan1 = !is_null($keterangan1) && !empty($keterangan1);
        if ($valid_keterangan1) {
            $keyValueToUpdate["keterangan1"] = $keterangan1;
        }

        // conditional $kabag
        $valid_keterangan2 = !is_null($keterangan2) && !empty($keterangan2);
        if ($valid_keterangan2) {
            $keyValueToUpdate["keterangan2"] = $keterangan2;
        }

        // conditional $periode
        $valid_periode = !is_null($periode) && !empty($periode);
        if ($valid_periode) {
            $keyValueToUpdate["periode"] = $periode;
        }

        // conditional $temuan
        $valid_temuan = !is_null($temuan) && !empty($temuan);
        if ($valid_temuan) {
            $keyValueToUpdate["temuan"] = $temuan;
        }

        // conditional $bagian
        $valid_bagian = !is_null($bagian) && !empty($bagian);
        if ($valid_bagian) {
            $keyValueToUpdate["bagian"] = $bagian;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_case_import->update_case_import_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_case_import->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update case import success',
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
                        'message' => 'Case import not found'
                    ), 404
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update case import, check the data you sent'
                )
            );
        }

        
    }
}
