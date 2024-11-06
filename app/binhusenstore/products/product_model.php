<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../admin_charge/admin_charge_model.php');

class Binhusenstore_product_model
{
    protected $database;
    var $table = "binhusenstore_products";
    var $table_archive = "binhusenstore_products_archived";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_product($name, $categories, $price, $weight, $images, $description, $default_total_week, $is_available, $links, $is_admin_charge)
    {

        $data_to_insert = array(
            'name' => $name,
            'categories' => $categories,
            'price' => $price,
            'weight' => $weight,
            'images' => $images,
            'description' => $description,
            'default_total_week' => $default_total_week,
            'is_available' => (int)$is_available,
            'is_admin_charge' => (int)$is_admin_charge,
            'links' => $links,
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_products($limit, $id_category = null, $name_product)
    {
        $columnToSelect = "id, images, name, price, default_total_week, is_admin_charge";

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

        $result = $this->database->select_where_match_full_text($this->table, $columnToSelect, $where_to_search, $what_to_search, "id", true, $limiter)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $convert_data_type_products = $this->convert_data_type($result);

            return $convert_data_type_products;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_product_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null && count($result) > 0) {

            $convert_data_type = $this->convert_data_type_detail($result);

            return $convert_data_type;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_product_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if ($this->database->is_error === null) {

            if ($result === 0) return $this->database->is_id_exists($this->table, $id);
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function remove_product_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_products_landing_page()
    {

        $table_product = $this->table;
        $result = array();

        // get categories first

        $categories = $this->database->select_where("binhusenstore_categories", "is_landing_page", 1, "id", true)->fetchAll(PDO::FETCH_ASSOC);
        $is_categories_exists = count($categories) > 0;

        if (!$is_categories_exists) return $result;

        // get products where category = cat, limit 4

        foreach ($categories as $value) {
            $category_id = $value['id'];
            $columnToSelect = "id, images, name, price, default_total_week";
            $get_products = $this->database->select_where_match_full_text($table_product, $columnToSelect, "categories", $category_id, "id", true, 4)->fetchAll(PDO::FETCH_ASSOC);

            $is_product_exists = count($get_products) > 0;

            if ($is_product_exists) {

                // mapping products
                $product_to_push = $this->convert_data_type($get_products);

                $array_to_push = array(
                    "category" => $value['name_category'],
                    "products" => $product_to_push
                );

                array_push($result, $array_to_push);
            }
        }

        $column_product_to_select = "id, images, name, price, default_total_week";
        $retrieve_3_new_product = $this->database->select_from($table_product, $column_product_to_select, "id", true, 3)->fetchAll(PDO::FETCH_ASSOC);

        $is_3_product_exists = count($retrieve_3_new_product) > 0;

        if ($is_3_product_exists) {

            // mapping products
            $product_to_push_3 = $this->convert_data_type($retrieve_3_new_product);

            $array_to_push = array(
                "category" => "Semua produk",
                "products" => $product_to_push_3
            );

            array_push($result, $array_to_push);
        }

        return $result;
    }

    public function move_product_to_archive($id_product)
    {
        // get product by id
        $retrieve_product = $this->get_product_by_id($id_product);

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
                'is_admin_charge' => (int)$retrieve_product[0]['is_admin_charge'],
            );

            $this->database->insert($this->table_archive, $data_to_insert);

            if ($this->database->is_error === null) {

                // remove product from products table
                $this->remove_product_by_id($id_product);
                return true;
            }

            $this->is_success = $this->database->is_error;
        }

        return false;
    }

    public function count_products()
    {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->database->sqlQuery($query)->fetchColumn();

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

            $images = $this->convert_image_url($product_value['images'], true);

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

    function convert_image_url($images_url, $is_first_image_large = false)
    {

        $result = array();

        $server_name = $_SERVER['SERVER_NAME'];
        $is_localhost = $server_name == 'localhost' || $server_name == '127.0.0.1';
        $host_url = $is_localhost ? "http://$server_name/rest-php/uploaded/binhusenstore/" : "https://$server_name/uploaded/binhusenstore/";

        $is_external_image = strpos($images_url, 'http') > -1;

        if ($is_external_image) {

            $result = explode(",", $images_url);
        } else {

            $image_as_arr = explode(",", $images_url);
            $index = 0;
            foreach ($image_as_arr as $image) {
                $image_name_to_push = $image;
                if ($index == 0 && $is_first_image_large) $image_name_to_push = str_replace("-small", "", $image);
                array_push($result, $host_url . $image_name_to_push);
                $index++;
            }
        }

        return $result;
    }

    public function retrieve_products_and_detail()
    {

        $result = $this->database->select_from($this->table);

        if ($this->database->is_error === null) {

            $convert_data_type_products = $this->convert_data_type_detail($result);

            return $convert_data_type_products;
        }

        $this->is_success = $this->database->is_error;
    }
}
