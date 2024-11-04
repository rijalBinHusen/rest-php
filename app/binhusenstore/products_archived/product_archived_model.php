<?php

require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../images/image_controller.php');

class Binhusenstore_product_archived_model
{
    protected $database;
    var $table_products = "binhusenstore_products";
    var $table_archived = "binhusenstore_products_archived";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function get_products_archived($limit, $id_category = null, $name_product)
    {
        $columnToSelect = "id, images, name, price, default_total_week, is_admin_charge";
        // $query = "SELECT $columnToSelect FROM $this->table";

        $is_category_valid = !is_null($id_category) && !empty($id_category) && $id_category != "";
        $is_name_product_valid = !is_null($name_product) && !empty($name_product) && $name_product != "";

        $where_to_search = false;
        $what_to_search = false;

        if ($is_category_valid) {
            $where_to_search = "categories";
            $what_to_search = $id_category;
        } else if ($is_name_product_valid) {
            $where_to_search = "name";
            $what_to_search = $name_product;
        }

        $limiter = 30;
        if (is_numeric($limit) && $limit > 0) $limiter = $limit;

        $result = $this->database->select_where_match_full_text($this->table_archived, $columnToSelect, $where_to_search, $what_to_search, "id", true, $limiter)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $convert_data_type_products = $this->convert_data_type($result);

            return $convert_data_type_products;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_product_archived_by_id($id)
    {

        $result = $this->database->select_where($this->table_archived, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($result) > 0) {

            $convert_data_type = $this->convert_data_type_detail($result);

            return $convert_data_type;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function remove_product_archived_by_id($id)
    {
        $product_info = $this->get_product_archived_by_id($id);

        $is_product_exists = is_array($product_info);
        if (!$is_product_exists) return;

        $images = explode(",", $product_info['images']);
        $image_controller = new Binhusenstore_image();

        foreach ($images as $image) {
            $image_controller->remove_image_operation($image);
        }

        $result = $this->database->delete($this->table_archived, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    private function convert_data_type($products)
    {
        $admin_charge_class = new Binhusenstore_admin_charge_model();
        $admin_charge = $admin_charge_class->retrieve_admin_charge();

        $result = array();
        // mapping products
        foreach ($products as $product_value) {
            // $product_name = strlen($product_value['name']) <= 44 ? $product_value['name'] : substr($product_value['name'], 0, 44) . "...";
            $product_name = $product_value['name'];
            $images = $this->convert_image_url($product_value['images']);

            $array_to_push = array(
                "id" => $product_value['id'],
                "name" => $product_name,
                "images" => $images,
                "price" => (int)$product_value['price'],
                "default_total_week" => (int)$product_value['default_total_week'],
            );

            if (array_key_exists('is_admin_charge', $product_value)) {

                $array_to_push['admin_charge'] = (int) $product_value['is_admin_charge'] ? $admin_charge : 0;
            }

            array_push($result, $array_to_push);
        }

        return $result;
    }

    private function convert_data_type_detail($products)
    {
        $admin_charge_class = new Binhusenstore_admin_charge_model();
        $admin_charge = $admin_charge_class->retrieve_admin_charge();

        $result = array();
        // mapping products
        foreach ($products as $product_value) {

            $images = $this->convert_image_url($product_value['images']);

            $array_to_push = array(
                'id' => $product_value['id'],
                'name' => $product_value['name'],
                'categories' => explode(",", $product_value['categories']),
                'price' => (int)$product_value['price'],
                'weight' => (int)$product_value['weight'],
                'images' => $images,
                'description' => $product_value['description'],
                'default_total_week' => (int)$product_value['default_total_week'],
                'is_available' => boolval($product_value['is_available']),
                'is_admin_charge' => boolval($product_value['is_admin_charge']),
                'links' => strlen($product_value['links']) ? explode(",", $product_value['links']) : array(),
                "admin_charge" => (int) $product_value['is_admin_charge'] ? $admin_charge : 0,
            );

            array_push($result, $array_to_push);
        }

        return $result;
    }

    function convert_image_url($images)
    {

        $result = array();

        $server_name = $_SERVER['SERVER_NAME'];
        $is_localhost = $server_name == 'localhost' || $server_name == '127.0.0.1';
        $host_url = $is_localhost ? "http://$server_name/rest-php/uploaded/binhusenstore/" : "https://$server_name/uploaded/binhusenstore/";

        $is_external_image = strpos($images, 'http') > -1;

        if ($is_external_image) {

            $result = explode(",", $images);
        } else {

            $image_as_arr = explode(",", $images);
            foreach ($image_as_arr as $image) {

                array_push($result, $host_url . $image);
            }
        }

        return $result;
    }

    public function move_product_from_archived_by_id($id_product)
    {
        // get product by id
        $retrieve_product = $this->get_product_archived_by_id($id_product);

        $is_product_exists = count($retrieve_product) > 0;
        if ($is_product_exists) {
            // create product to archived table
            $data_to_insert = array(
                'id' => $id_product,
                'name' => $retrieve_product[0]['name'],
                'categories' => implode(",", $retrieve_product[0]['categories']),
                'price' => $retrieve_product[0]['price'],
                'weight' => $retrieve_product[0]['weight'],
                'images' => implode(",", $retrieve_product[0]['images']),
                'description' => $retrieve_product[0]['description'],
                'default_total_week' => $retrieve_product[0]['default_total_week'],
                'is_available' => (int)$retrieve_product[0]['is_available'],
                'links' => implode(",", $retrieve_product[0]['links']),
            );

            // insert to products
            $this->database->insert($this->table_products, $data_to_insert);

            if ($this->database->is_error === null) {

                // remove product from archived products table
                return $this->remove_product_archived_by_id($id_product);
            }

            $this->is_success = $this->database->is_error;
        }

        return false;
    }
}
