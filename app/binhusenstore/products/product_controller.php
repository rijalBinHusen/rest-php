<?php
require_once(__DIR__ . '/base_file_model.php');

class My_report_base_file
{
    protected $my_report_base_file;
    function __construct()
    {
        $this->my_report_base_file = new My_report_base_file_model();
    }
    public function get_base_files()
    { 
        $request = Flight::request();
        $periode1 = $request->query->periode1;
        $periode2 = $request->query->periode2;

        $not_valid_query_string = is_null($periode1) || empty($periode1) || is_null($periode2) || empty($periode2) || !is_numeric($periode1) || !is_numeric($periode2);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }
        // the query string is valid

        $result = $this->my_report_base_file->get_base_files($periode1, $periode2);
        
        $is_exists = count($result) > 0;

        if($this->my_report_base_file->is_success === true && $is_exists) {
            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);
        }

        else if ($this->my_report_base_file->is_success !== true) {
            Flight::json( array(
                "success" => false,
                "message" => $result
            ), 500);
        }
        
        else {
            Flight::json( array(
            "success" => false,
            "message" => "Base file not found"
            ), 404);
        }

    }
    public function add_base_file()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $periode = $req->data->periode;
        $warehouse_id = $req->data->warehouse_id;
        $file_name = $req->data->file_name;
        $stock_sheet = $req->data->stock_sheet;
        $clock_sheet = $req->data->clock_sheet;
        $is_imported = $req->data->is_imported;
        $is_record_finished = $req->data->is_record_finished;

        $result = null;

        $is_request_body_oke = !is_null($warehouse_id) 
                                && !is_null($periode)
                                && !is_null($file_name) 
                                && !is_null($stock_sheet) 
                                && !is_null($clock_sheet) 
                                && !is_null($is_imported)
                                && !is_null($is_record_finished);

        if($is_request_body_oke) {
            if ($id) {
                // write the warehouse
                $result = $this->my_report_base_file->write_base_file($id, $periode, $warehouse_id, $file_name, $stock_sheet, $clock_sheet, $is_imported, $is_record_finished);
            } else {
                // append warehouse
                $result = $this->my_report_base_file->append_base_file($periode, $warehouse_id, $file_name, $stock_sheet, $clock_sheet, $is_imported, $is_record_finished);
            }

            if($this->my_report_base_file->is_success !== true) {
                Flight::json(
                    array(
                        'success'=> false,
                        'message'=> $this->my_report_base_file->is_success
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
                'message' => 'Failed to add base file, check the data you sent',
                'data' => array($warehouse_id, $periode
                , $file_name, $stock_sheet, $clock_sheet, $is_imported, $is_record_finished)
            ), 400
        );
    }
    public function get_base_file_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_file->get_base_file_by_id($id);

        $is_success = $this->my_report_base_file->is_success;

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
                    'message' => 'Base file not found'
                ), 404
            );
        }
    }

    public function remove_base_file($id) {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_base_file->remove_base_file($id);

        $is_success = $this->my_report_base_file->is_success;
    
        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete base file success',
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
                    'message' => 'Base file not found'
                ), 404
            );
        }
    }

    public function update_base_file_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $warehouse_id = $req->data->warehouse_id;
        $periode = $req->data->periode;
        $file_name = $req->data->file_name;
        $stock_sheet = $req->data->stock_sheet;
        $clock_sheet = $req->data->clock_sheet;
        $is_imported = $req->data->is_imported;
        $is_record_finished = $req->data->is_record_finished;

        // initiate the column and values to update
        $keyValueToUpdate = array();
        // conditional warehouse_id
        $valid_warehouse_id = !is_null($warehouse_id) && !empty($warehouse_id);
        if ($valid_warehouse_id) {
            $keyValueToUpdate["warehouse_id"] = $warehouse_id;
        }

        // conditional $file_name
        $valid_file_name = !is_null($file_name);
        if ($valid_file_name) {
            $keyValueToUpdate["file_name"] = $file_name;
        }

        // conditional $stock_sheet
        $valid_stock_sheet = !is_null($stock_sheet);
        if ($valid_stock_sheet) {
            $keyValueToUpdate["stock_sheet"] = $stock_sheet;
        }

        // conditional $clock_sheet
        $valid_clock_sheet = !is_null($clock_sheet);
        if ($valid_clock_sheet) {
            $keyValueToUpdate["clock_sheet"] = $clock_sheet;
        }

        // conditional $is_imported
        $valid_is_imported = !is_null($is_imported);
        if ($valid_is_imported) {
            $keyValueToUpdate["is_imported"] = $is_imported;
        }

        // conditional $periode
        $valid_periode = !is_null($periode);
        if ($valid_periode) {
            $keyValueToUpdate["periode"] = $periode;
        }

        // conditional $is_record_finished
        $valid_is_record_finished = !is_null($is_record_finished);
        if ($valid_is_record_finished) {
            $keyValueToUpdate["is_record_finished"] = $is_record_finished;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_base_file->update_base_file_by_id($keyValueToUpdate, "id", $id);
    
            $is_success = $this->my_report_base_file->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update base file success',
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
                        'message' => 'Base file not found'
                    ), 404
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update base file, check the data you sent'
                )
            );
        }

        
    }
}
