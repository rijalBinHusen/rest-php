<?php
require_once(__DIR__ . '/document_model.php');

class My_report_document
{
    protected $my_report_document;
    function __construct()
    {
        $this->my_report_document = new My_report_document_model();
    }

    public function add_document()
    {
        // request
        $req = Flight::request();
        $id = $req->data->id;
        $collected = $req->data->collected;
        $approval = $req->data->approval;
        $status = $req->data->status;
        $shared = $req->data->shared;
        $finished = $req->data->finished;
        $total_do = $req->data->total_do;
        $total_kendaraan = $req->data->total_kendaraan;
        $total_waktu = $req->data->total_waktu;
        $base_report_file = $req->data->base_report_file;
        $is_finished = $req->data->is_finished;
        $supervisor_id = $req->data->supervisor_id;
        $periode = $req->data->periode;
        $shift = $req->data->shift;
        $head_spv_id = $req->data->head_spv_id;
        $warehouse_id = $req->data->warehouse_id;
        $is_generated_document = $req->data->is_generated_document;
        $item_variance = $req->data->item_variance;
        $parent = $req->data->parent;
        $parent_document = $req->data->parent_document;
        $plan_out = $req->data->plan_out;
        $total_item_keluar = $req->data->total_item_keluar;
        $total_item_moving = $req->data->total_item_moving;
        $total_product_not_FIFO = $req->data->total_product_not_FIFO;
        $total_qty_in = $req->data->total_qty_in;
        $total_qty_out = $req->data->total_qty_out;

        $result = null;

        $valid_request_body =   !is_null($collected) 
                                && !is_null($approval) 
                                && !is_null($status) 
                                && !is_null($shared) 
                                && !is_null($finished) 
                                && !is_null($total_do) 
                                && !is_null($total_kendaraan)
                                && !is_null($total_waktu)
                                && !is_null($base_report_file)
                                && !is_null($is_finished)
                                && !is_null($supervisor_id)
                                && !is_null($periode)
                                && !is_null($shift)
                                && !is_null($head_spv_id)
                                && !is_null($warehouse_id)
                                && !is_null($is_generated_document)
                                && !is_null($item_variance)
                                && !is_null($parent)
                                && !is_null($parent_document)
                                && !is_null($plan_out)
                                && !is_null($total_item_keluar)
                                && !is_null($total_item_moving)
                                && !is_null($total_product_not_FIFO)
                                && !is_null($total_qty_in)
                                && !is_null($total_qty_out);
        
            if($valid_request_body) {
                if ($id) {
                    // write the warehouse
                    $result = $this->my_report_document->write_document(
                        $id, 
                        $collected, 
                        $approval, 
                        $status, 
                        $shared, 
                        $finished, 
                        $total_do, 
                        $total_kendaraan,
                        $total_waktu,
                        $base_report_file,
                        $is_finished,
                        $supervisor_id,
                        $periode,
                        $shift,
                        $head_spv_id,
                        $warehouse_id,
                        $is_generated_document,
                        $item_variance,
                        $parent,
                        $parent_document,
                        $plan_out,
                        $total_item_keluar,
                        $total_item_moving,
                        $total_product_not_FIFO,
                        $total_qty_in,
                        $total_qty_out
                        );
                } 
                
                else {
                    // append warehouse
                    $result = $this->my_report_document->append_document(
                                                                $collected, 
                                                                $approval, 
                                                                $status, 
                                                                $shared, 
                                                                $finished, 
                                                                $total_do, 
                                                                $total_kendaraan,
                                                                $total_waktu,
                                                                $base_report_file,
                                                                $is_finished,
                                                                $supervisor_id,
                                                                $periode,
                                                                $shift,
                                                                $head_spv_id,
                                                                $warehouse_id,
                                                                $is_generated_document,
                                                                $item_variance,
                                                                $parent,
                                                                $parent_document,
                                                                $plan_out,
                                                                $total_item_keluar,
                                                                $total_item_moving,
                                                                $total_product_not_FIFO,
                                                                $total_qty_in,
                                                                $total_qty_out
                                                            );
                }

                if($this->my_report_document->is_success !== true) {
                    Flight::json(
                        array(
                            'success'=> false,
                            'message'=> $this->my_report_document->is_success
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
                'message' => 'Failed to add document, check the data you sent'
            ), 400
        );
    }

    public function get_document_by_periode()
    { 
        $request = Flight::request();
        $periode1 = $request->query->periode1;
        $periode2 = $request->query->periode2;

        $not_valid_query_string = is_null($periode1) 
                                    || empty($periode1) 
                                    || !is_numeric($periode1) 
                                    || is_null($periode2) 
                                    || empty($periode2) 
                                    || !is_numeric($periode2);

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_document->get_documents_by_periode($periode1, $periode2);
        
        $is_exists = count($result) > 0;

        if($this->my_report_document->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_document->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Document not found"
                )
            , 404);
        }

    }

    public function get_document_by_status()
    { 
        $request = Flight::request();
        $status = $request->query->status;

        $not_valid_query_string = is_null($status)
                                    || !is_numeric($status) 
                                    || $status < -1 
                                    || $status > 3;

        if($not_valid_query_string) {
            Flight::json( array(
                "success" => false,
                "message" => "Please check query parameter"
                )
            , 400);

            return;

        }

        // the query string is valid

        $result = $this->my_report_document->get_documents_by_status($status);
        
        $is_exists = count($result) > 0;

        if($this->my_report_document->is_success === true && $is_exists) {

            Flight::json(
                array(
                    "success" => true,
                    "data" => $result
                    )
            , 200);

        }

        else if ($this->my_report_document->is_success !== true) {

            Flight::json( 
                array(
                    "success" => false,
                    "message" => $result
                )
            , 500);

        }
        
        else {
            Flight::json( 
                array(
                    "success" => false,
                    "message" => "Document not found"
                )
            , 404);
        }

    }

    public function get_document_by_id($id)
    {
        // myguest/8
        // the 8 will automatically becoming parameter $id
        $result = $this->my_report_document->get_document_by_id($id);

        $is_success = $this->my_report_document->is_success;

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
                    'message' => 'Document not found'
                )
            );
        }
    }
    
    public function update_document_by_id($id)
    {
        // catch the query string request
        $req = Flight::request();
        $collected = $req->data->collected;
        $approval = $req->data->approval;
        $status = $req->data->status;
        $shared = $req->data->shared;
        $finished = $req->data->finished;
        $total_do = $req->data->total_do;
        $total_kendaraan = $req->data->total_kendaraan;
        $total_waktu = $req->data->total_waktu;
        $base_report_file = $req->data->base_report_file;
        $is_finished = $req->data->is_finished;
        $supervisor_id = $req->data->supervisor_id;
        $periode = $req->data->periode;
        $shift = $req->data->shift;
        $head_spv_id = $req->data->head_spv_id;
        $warehouse_id = $req->data->warehouse_id;
        $is_generated_document = $req->data->is_generated_document;
        $item_variance = $req->data->item_variance;
        $parent = $req->data->parent;
        $parent_document = $req->data->parent_document;
        $plan_out = $req->data->plan_out;
        $total_item_keluar = $req->data->total_item_keluar;
        $total_item_moving = $req->data->total_item_moving;
        $total_product_not_FIFO = $req->data->total_product_not_FIFO;
        $total_qty_in = $req->data->total_qty_in;
        $total_qty_out = $req->data->total_qty_out;

        // initiate the column and values to update
        $keyValueToUpdate = array();

        // conditional $collected
        $valid_collected = !is_null($collected) && !empty($collected);
        if ($valid_collected) {
            $keyValueToUpdate["collected"] = $collected;
        }

        // conditional approval
        $valid_approval = !is_null($approval) && !empty($approval);
        if ($valid_approval) {
            $keyValueToUpdate["approval"] = $approval;
        }

        // conditional $status
        $valid_status = !is_null($status) && !empty($status);
        if ($valid_status) {
            $keyValueToUpdate["status"] = $status;
        }

        // conditional $shared
        $valid_shared = !is_null($shared) && !empty($shared);
        if ($valid_shared) {
            $keyValueToUpdate["shared"] = $shared;
        }

        // conditional $finished
        $valid_finished = !is_null($finished) && !empty($finished);
        if ($valid_finished) {
            $keyValueToUpdate["finished"] = $finished;
        }

        // conditional $total_do
        $valid_total_do = !is_null($total_do) && !empty($total_do);
        if ($valid_total_do) {
            $keyValueToUpdate["total_do"] = $total_do;
        }

        // conditional $total_kendaraan
        $valid_total_kendaraan = !is_null($total_kendaraan) && !empty($total_kendaraan);
        if ($valid_total_kendaraan) {
            $keyValueToUpdate["total_kendaraan"] = $total_kendaraan;
        }

        // conditional $total_waktu
        $valid_dl = !is_null($total_waktu) && !empty($total_waktu);
        if ($valid_dl) {
            $keyValueToUpdate["total_waktu"] = $total_waktu;
        }

        // conditional $base_report_file
        $valid_masalah = !is_null($base_report_file) && !empty($base_report_file);
        if ($valid_masalah) {
            $keyValueToUpdate["base_report_file"] = $base_report_file;
        }

        // conditional $is_finished
        $valid_is_finished = !is_null($is_finished) && !empty($is_finished);
        if ($valid_is_finished) {
            $keyValueToUpdate["is_finished"] = $is_finished;
        }

        // conditional $supervisor_id
        $valid_supervisor_id = !is_null($supervisor_id) && !empty($supervisor_id);
        if ($valid_supervisor_id) {
            $keyValueToUpdate["supervisor_id"] = $supervisor_id;
        }

        // conditional $periode
        $valid_periode = !is_null($periode) && !empty($periode);
        if ($valid_periode) {
            $keyValueToUpdate["periode"] = $periode;
        }

        // conditional $shift
        $valid_shift = !is_null($shift) && !empty($shift);
        if ($valid_shift) {
            $keyValueToUpdate["shift"] = $shift;
        }

        // conditional $head_spv_id
        $valid_head_spv_id = !is_null($head_spv_id) && !empty($head_spv_id);
        if ($valid_head_spv_id) {
            $keyValueToUpdate["head_spv_id"] = $head_spv_id;
        }

        // conditional $warehouse_id
        $valid_warehouse_id = !is_null($warehouse_id) && !empty($warehouse_id);
        if ($valid_warehouse_id) {
            $keyValueToUpdate["warehouse_id"] = $warehouse_id;
        }

        // conditional $is_generated_document
        $valid_is_generated_document = !is_null($is_generated_document) && !empty($is_generated_document);
        if ($valid_is_generated_document) {
            $keyValueToUpdate["is_generated_document"] = $is_generated_document;
        }

        // conditional $item_variance
        $valid_item_variance = !is_null($item_variance) && !empty($item_variance);
        if ($valid_item_variance) {
            $keyValueToUpdate["item_variance"] = $item_variance;
        }

        // conditional $parent
        $valid_parent = !is_null($parent) && !empty($parent);
        if ($valid_parent) {
            $keyValueToUpdate["parent"] = $parent;
        }

        // conditional $parent_document
        $valid_parent_document = !is_null($parent_document) && !empty($parent_document);
        if ($valid_parent_document) {
            $keyValueToUpdate["parent_document"] = $parent_document;
        }

        // conditional $plan_out
        $valid_plan_out = !is_null($plan_out) && !empty($plan_out);
        if ($valid_plan_out) {
            $keyValueToUpdate["plan_out"] = $plan_out;
        }

        // conditional $total_item_keluar
        $valid_total_item_keluar = !is_null($total_item_keluar) && !empty($total_item_keluar);
        if ($valid_total_item_keluar) {
            $keyValueToUpdate["total_item_keluar"] = $total_item_keluar;
        }

        // conditional $total_item_moving
        $valid_total_item_moving = !is_null($total_item_moving) && !empty($total_item_moving);
        if ($valid_total_item_moving) {
            $keyValueToUpdate["total_item_moving"] = $total_item_moving;
        }

        // conditional $total_product_not_FIFO
        $valid_total_product_not_FIFO = !is_null($total_product_not_FIFO) && !empty($total_product_not_FIFO);
        if ($valid_total_product_not_FIFO) {
            $keyValueToUpdate["total_product_not_FIFO"] = $total_product_not_FIFO;
        }

        // conditional $total_qty_in
        $valid_total_qty_in = !is_null($total_qty_in) && !empty($total_qty_in);
        if ($valid_total_qty_in) {
            $keyValueToUpdate["total_qty_in"] = $total_qty_in;
        }

        // conditional $total_qty_out
        $valid_total_qty_out = !is_null($total_qty_out) && !empty($total_qty_out);
        if ($valid_total_qty_out) {
            $keyValueToUpdate["total_qty_out"] = $total_qty_out;
        }

        $is_oke_to_update = count($keyValueToUpdate) > 0;

        if($is_oke_to_update) {

            $result = $this->my_report_document->update_document_by_id($keyValueToUpdate, $id);
    
            $is_success = $this->my_report_document->is_success;
    
            if($is_success === true && $result > 0) {
                Flight::json(
                    array(
                        'success' => true,
                        'message' => 'Update document success',
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
                        'message' => "Document not found",
                    )
                );
            }
        } 
        
        else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => 'Failed to update document, check the data you sent'
                )
            );
        }

        
    }

    public function remove_document_by_id($id) {
        $result = $this->my_report_document->remove_document_by_id($id);

        $is_success = $this->my_report_document->is_success;

        if($is_success === true && $result > 0) {
            Flight::json(
                array(
                    'success' => true,
                    'message' => 'Delete document success',
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
                    'message' => 'Document not found'
                )
            );
        }
    }

    public function last_date() {
        $result = $this->my_report_document->last_document_date();

        if($this->my_report_document->is_success === true) {
            Flight::json(
                array(
                    'success' => true,
                    'last_date' => $result,
                )
            );
        } else {
            Flight::json(
                array(
                    'success' => false,
                    'message' => $this->my_report_document->is_success
                )
            );
        }
    }
}
