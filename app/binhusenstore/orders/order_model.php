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

        if ($admin_chrage) {
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
        } else if ($is_error) {

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

    public function move_order_to_archive($id_order, $phone)
    {

        $retrieve_order = $this->get_order_by_id($id_order);
        $phone_decrypted =  decrypt_string($retrieve_order[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);

        $is_order_exists = count($retrieve_order) > 0;
        $is_phone_matched = $phone_decrypted === $phone;

        if (!$is_phone_matched) return "Nomor telfon pengguna tidak sesuai dengan database!";
        if (!$is_order_exists) return 0;

        $is_oke_to_continue = $is_order_exists && $is_phone_matched;

        if ($is_oke_to_continue) {

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

                $payment_model = new Binhusenstore_payment_model();
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

    public function merge_order_as_group($id_order_1, $id_order_2)
    {

        $order_1 = $this->database->select_where($this->table, 'id', $id_order_1)->fetchAll(PDO::FETCH_ASSOC);
        $order_2 = $this->database->select_where($this->table, 'id', $id_order_2)->fetchAll(PDO::FETCH_ASSOC);

        $is_order_exists = count($order_1) > 0 && count($order_2) > 0;
        if (!$is_order_exists) return 0;

        $phone_1_decrypted =  decrypt_string($order_1[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);
        $phone_2_decrypted =  decrypt_string($order_2[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);

        $is_phone_matched = $phone_1_decrypted == $phone_2_decrypted;
        if (!$is_phone_matched) return "Nomor handphone pemesan tidak sama";

        $id_group_to_set = str_replace("O", "G", $id_order_1);

        $is_order_1_has_group_id = boolval($order_1[0]['is_group']);
        $is_order_2_has_group_id = boolval($order_2[0]['is_group']);

        if ($is_order_1_has_group_id) $id_group_to_set = $order_1[0]['id_group'];
        if ($is_order_2_has_group_id) $id_group_to_set = $order_2[0]['id_group'];

        $is_both_order_has_group_id = $is_order_1_has_group_id && $is_order_2_has_group_id;
        if ($is_both_order_has_group_id) return "Semua order telah memiliki group masing masing";

        $data_to_set = array(
            'id_group' => $id_group_to_set,
            'is_group' => true
        );

        $payment_model = new Binhusenstore_payment_model();

        //
        if ($is_order_1_has_group_id) {

            $payment_model->add_id_group_payment_by_id_order($id_order_2, $id_group_to_set);
            return $this->update_order_by_id($data_to_set, 'id', $id_order_2);
        }
        //
        else if ($is_order_2_has_group_id) {

            $payment_model->add_id_group_payment_by_id_order($id_order_1, $id_group_to_set);
            return $this->update_order_by_id($data_to_set, 'id', $id_order_1);
        }
        //
        else if (!$is_both_order_has_group_id) {

            $payment_model->add_id_group_payment_by_id_order($id_order_1, $id_group_to_set);
            $payment_model->add_id_group_payment_by_id_order($id_order_2, $id_group_to_set);
            $this->update_order_by_id($data_to_set, 'id', $id_order_2);
            return $this->update_order_by_id($data_to_set, 'id', $id_order_1);
        }
        //
        else {

            $this->is_success = $this->database->is_error;
        }
    }
}
