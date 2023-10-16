<?php
require_once(__DIR__ . '/../../../utils/database.php');

class Binhusenstore_product_model
{
    protected $database;
    var $table = "binhusenstore_products";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_product($name, $categories, $price, $weight, $images, $description, $default_total_week, $is_available, $links)
    {

        $data_to_insert = array(
            'name' => $name,
            'categories' => $categories,
            'price' => $price,
            'weight' => $weight,
            'images' => $images,
            'description' => $description,
            'default_total_week' => $default_total_week,
            'is_available' => $is_available,
            'links' => $links,
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_products($limit = 0)
    {
        $columnToSelect = "id, images, name, price, default_total_week";
        $query = "SELECT $columnToSelect FROM $this->table ORDER BY id DESC";

        if ($limit > 0) {
            $query = $query . " LIMIT " . $limit;
        } else {
            $query = $query . " LIMIT 30";
        }
        $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $convert_data_type_products = $this->convert_data_type($result);

            return $convert_data_type_products;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_product_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return [
                array(
                    'name' => $result[0]['name'],
                    'categories' => explode(",", $result[0]['categories']),
                    'price' => (int)$result[0]['price'],
                    'weight' => (int)$result[0]['weight'],
                    'images' => explode(",", $result[0]['images']),
                    'description' => $result[0]['description'],
                    'default_total_week' => (int)$result[0]['default_total_week'],
                    'is_available' => boolval($result[0]['is_available']),
                    'links' => explode(",", $result[0]['links']),
                )
            ];
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_product_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if ($this->database->is_error === null) {

            if ($result === 0) {

                $query = "SELECT EXISTS(SELECT id FROM $this->table WHERE id = '$id')";
                return $this->database->sqlQuery($query)->fetchColumn();
            }

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
        $categories = $this->database->select_where("binhusenstore_categories", 'is_landing_page', 1)->fetchAll(PDO::FETCH_ASSOC);
        $is_categories_exists = count($categories) > 0;

        if (!$is_categories_exists) {
            return $result;
        };

        // get products where category = cat, limit 4

        foreach ($categories as $value) {
            $category_id = $value['id'];
            $columnToSelect = "id, images, name, price, default_total_week";
            $query_product = "SELECT $columnToSelect FROM $table_product WHERE MATCH(categories) AGAINST ('$category_id' IN NATURAL LANGUAGE MODE) ORDER BY id DESC LIMIT 4";
            $get_products = $this->database->sqlQuery($query_product)->fetchAll(PDO::FETCH_ASSOC);

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
        $query_3_product = "SELECT $column_product_to_select FROM $table_product ORDER BY id DESC LIMIT 3";
        $retrieve_3_new_product = $this->database->sqlQuery($query_3_product)->fetchAll(PDO::FETCH_ASSOC);

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
        $result = array();

        // mapping products
        foreach ($products as $product_value) {
            array_push($result, array(
                "id" => $product_value['id'],
                "name" => substr($product_value['name'], 0, 44) . "...",
                "images" => explode(",", $product_value['images']),
                "price" => (int)$product_value['price'],
                "default_total_week" => (int)$product_value['default_total_week'],
            ));
        }

        return $result;
    }
}
