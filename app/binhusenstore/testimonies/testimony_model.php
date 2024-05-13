<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../products/product_model.php');

class Binhusenstore_testimony_model
{
    protected $database;
    var $table = "binhusenstore_testimonies";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_testimony($id_user, $id_product, $rating, $content, $display_name)
    {

        $data_to_insert = array(
            'id_user' => $id_user,
            'display_name' => $display_name,
            'id_product' => $id_product,
            'rating' => $rating,
            'content' => $content
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_testimonies($limit)
    {

        $table_testimony = $this->table;

        $limiter = 10;
        if (is_numeric($limit) && $limit > 1) $limiter = $limit;

        $result = $this->database->select_from($table_testimony, "*", "id", true, $limiter)->fetchAll(PDO::FETCH_ASSOC);
        if ($this->database->is_error === null && count($result) > 0) {

            $converted_data_type = $this->convert_data_type($result);
            return $converted_data_type;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_testimoniesByIdProduct($id_product)
    {
        $table_testimony = $this->table;
        $result = $this->database->select_where($table_testimony, "id", $id_product, "id", true)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $converted_data_type = $this->convert_data_type($result);
            return $converted_data_type;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_testimony_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {


            $converted_data_type = $this->convert_data_type($result);
            return $converted_data_type;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_testimony_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if ($this->database->is_error === null) {

            if ($result === 0) return $this->database->is_id_exists($this->table, $id);
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function remove_testimony_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }


    public function get_testimony_landing_page()
    {

        $table_testimony = $this->table;
        $query_testimony = "SELECT * FROM $table_testimony ORDER BY RAND() LIMIT 1";
        $result = $this->database->sqlQuery($query_testimony)->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) === 0) return array();

        $product_model = new Binhusenstore_product_model();
        $retrieve_product = $product_model->get_product_by_id($result[0]['id_product']);

        if (count($retrieve_product) === 0) return array();

        if ($this->database->is_error === null && count($result) > 0) {

            return array(
                'id' => $result[0]['id'],
                'display_name' => $result[0]['display_name'],
                'rating' => (int)$result[0]['rating'],
                'content' => $result[0]['content'],
                'product_name' => $retrieve_product[0]['name'],
                'product_image' => $retrieve_product[0]['images'][0]
            );
        }

        $this->is_success = $this->database->is_error;
    }


    public function convert_data_type($testimonies)
    {
        $result = array();

        foreach ($testimonies as $value) {
            array_push($result, array(
                'id' => $value['id'],
                'display_name' => $value['display_name'],
                'id_product' => $value['id_product'],
                'rating' => (int)$value['rating'],
                'content' => $value['content'],
            ));
        }

        return $result;
    }
}
