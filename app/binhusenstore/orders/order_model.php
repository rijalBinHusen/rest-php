<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../../../utils/piece/encrypt_decrypt_str.php');
require_once(__DIR__ . '/../payments/payments_model.php');

class Binhusenstore_order_model
{
    protected $database;
    var $table = "binhusenstore_orders";
    var $table_archive = "binhusenstore_orders_archived";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_order($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance, $phone, $admin_chrage)
    {
        $encrypted_phone = encrypt_string($phone, ENCRYPT_DECRYPT_PHONE_KEY);

        $data_to_insert = array(
            'date_order' => $date_order,
            'id_group' => $id_group,
            'is_group' => (int)$is_group,
            'id_product' => $id_product,
            'name_of_customer' => $name_of_customer,
            'sent' => $sent,
            'title' => $title,
            'total_balance' => $total_balance,
            'phone' => $encrypted_phone,
            'admin_charge' => 0
        );

        if($admin_chrage) {
            // retrieve admin charge
            $retrieve_charge = $this->database->select_where('admin_charge', 'domain', 'binhusenstore')->fetchAll(PDO::FETCH_ASSOC);

            // set the admin charge
            if ($retrieve_charge) {

                $data_to_insert['admin_charge'] = $retrieve_charge[0]['admin_charge'];
            }
        }

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_orders($limit)
    {
        $columnToSelect = "id, date_order, id_group, is_group, id_product, name_of_customer, sent, title, total_balance";
        $query = "SELECT $columnToSelect FROM $this->table";

        $query = $query . " ORDER BY id DESC";

        if ($limit > 0) $query = $query . " LIMIT " . $limit;
        else $query = $query . " LIMIT 30";


        $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        if ($this->database->is_error === null) return $result;

        $this->is_success = $this->database->is_error;
    }

    public function get_order_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_order_by_id(array $data, $where, $id)
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

    public function remove_order_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function phone_by_order_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        $is_error = $this->database->is_error !== null;

        if (!$is_error && count($result) > 0) {

            $phone = $result[0]['phone'];
            $decrypted_phone = decrypt_string($phone, ENCRYPT_DECRYPT_PHONE_KEY);
            return $decrypted_phone;
        }

        else if($is_error) {

            $this->is_success = $this->database->is_error;
        }

        return false;
    }

    public function count_orders()
    {
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->database->sqlQuery($query)->fetchColumn();

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function move_order_to_archive($id_order)
    {
        $payment_model = new Binhusenstore_payment_model();

        $retrieve_order = $this->get_order_by_id($id_order);

        $is_order_exists = count($retrieve_order) > 0;

        if ($is_order_exists) {

            //set data to insert
            $data_to_insert = array(
                'id' => $id_order,
                'date_order' => $retrieve_order[0]['date_order'],
                'id_group' => $retrieve_order[0]['id_group'],
                'is_group' => (int)$retrieve_order[0]['is_group'],
                'id_product' => $retrieve_order[0]['id_product'],
                'name_of_customer' => $retrieve_order[0]['name_of_customer'],
                'sent' => $retrieve_order[0]['sent'],
                'title' => $retrieve_order[0]['title'],
                'total_balance' => $retrieve_order[0]['total_balance'],
                'phone' => $retrieve_order[0]['phone']
            );

            // insert to archive table
            $this->database->insert($this->table_archive, $data_to_insert);

            if ($this->database->is_error === null) {

                // remove order
                $this->remove_order_by_id($id_order);
                $is_payment_moved = $payment_model->move_payment_to_archive($id_order);
                if ($is_payment_moved === true) {

                    return true;
                } else {

                    $this->is_success = $is_payment_moved;
                    return false;
                }
            }

            $this->is_success = $this->database->is_error;
        }

        return false;
    }
}
