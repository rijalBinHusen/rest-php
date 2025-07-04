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

    // public function append_order($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance, $phone, $admin_chrage)
    // {
    //     $encrypted_phone = encrypt_string($phone, ENCRYPT_DECRYPT_PHONE_KEY);

    //     $data_to_insert = array(
    //         'date_order' => $date_order,
    //         'id_group' => $id_group,
    //         'is_group' => (int)$is_group,
    //         'id_product' => $id_product,
    //         'name_of_customer' => $name_of_customer,
    //         'sent' => $sent,
    //         'title' => $title,
    //         'total_balance' => $total_balance,
    //         'phone' => $encrypted_phone,
    //         'admin_charge' => 0
    //     );

    //     if ($admin_chrage) {
    //         // retrieve admin charge
    //         $retrieve_charge = $this->database->select_where('admin_charge', 'domain', 'binhusenstore')->fetchAll(PDO::FETCH_ASSOC);

    //         // set the admin charge
    //         if ($retrieve_charge) {

    //             $data_to_insert['admin_charge'] = $retrieve_charge[0]['admin_charge'];
    //         }
    //     }

    //     $this->database->insert($this->table, $data_to_insert);

    //     if ($this->database->is_error === null) {

    //         return $this->database->getMaxId($this->table);
    //     }

    //     $this->is_success = $this->database->is_error;
    // }

    public function append_order($date_order, $id_group, $is_group, $id_product, $name_of_customer, $sent, $title, $total_balance, $phone, $admin_chrage, $start_date_payment, $balance_payment, $week_distance, $date_end)
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
            'admin_charge' => 0,
            'date_end' => $date_end,
            'payment_period_distance' => $week_distance,
            'payment_per_period' => $balance_payment
        );

        if ($admin_chrage) {
            // retrieve admin charge
            $retrieve_charge = $this->database->select_where('admin_charge', 'domain', 'binhusenstore')->fetchAll(PDO::FETCH_ASSOC);

            // set the admin charge
            if ($retrieve_charge) {

                $data_to_insert['admin_charge'] = $retrieve_charge[0]['admin_charge'];
                $data_to_insert['total_balance'] = $total_balance + $retrieve_charge[0]['admin_charge'];
            }
        }

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            $id_order = $this->database->getMaxId($this->table);

            //     $payment_model = new Binhusenstore_payment_model();
            //     $balance_remaining = $data_to_insert['total_balance'];
            //     $is_payment_created = false;

            //     $current_date = new DateTime($start_date_payment);
            //     $dayPlus = $week_distance * 7;

            //     while ($balance_remaining > 0) {

            //         $balance_to_insert = $balance_remaining >= $balance_payment ? $balance_payment : $balance_remaining;
            //         $is_payment_created = $payment_model->append_payment($current_date->format('Y-m-d'), $id_order, $balance_to_insert, "");
            //         $balance_remaining = $balance_remaining - $balance_payment;

            //         $current_date->modify('+' . $dayPlus . 'day');
            //         if (!$is_payment_created) {
            //             $this->is_success = $payment_model->is_success;
            //             return "Failed to create payment";
            //         }
            //     }

            return $id_order;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_orders($limit)
    {
        $columnToSelect = "id, date_order, id_group, is_group, id_product, name_of_customer, sent, title, total_balance, admin_charge, payment_per_period";
        $query = "SELECT $columnToSelect FROM $this->table";

        $query = $query . " ORDER BY id DESC";

        if (is_numeric($limit) && $limit > 0) $query = $query . " LIMIT " . $limit;

        $result = $this->database->sqlQuery($query)->fetchAll(PDO::FETCH_ASSOC);
        if ($this->database->is_error === null) return $result;

        $this->is_success = $this->database->is_error;
    }

    /**
     * Get order dashboard by id_prder.
     *
     * @param $id number.
     * @return array(
     * "day_percent": => number,
     * "day_remaining": => number,
     * "total_balance_percent": => number,
     * "total_payments_count": => number,
     * "date_order": => string,
     * "name_of_customer": => string,
     * "title": => string,
     * "total_balance": => number,
     * "total_balance_paid": => number,
     * "admin_charge": => number,
     * "payment_period_distance" => number,
     * "payments": => [
     * {
     * "id": => string,
     * "order_number": => number,
     * "date_paid": => string,
     * "balance": => number
     * }).
     */

    public function get_order_dashboard_by_id($id)
    {
        $payment_model = new Binhusenstore_payment_model();

        $summary_order_table = "binhusenstore_order_summary";
        $order_info = $this->database->select_where($summary_order_table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);
        if (count($order_info) == 0) return array();
        $payments = $payment_model->get_paid_and_order_data_by_date_payment_desc($id);
        // if (count($payments) == 0) return array();

        // pick only 5 first payments
        $payments_to_return = array(); // Start at index 0, take 5 elements

        for ($i = 0; $i < 5; $i++) {
            if (count($payments) < ($i + 1)) continue;
            $data = $payments[$i];
            $array_to_push = array(
                "date_paid" => $data['date_paid'],
                "total_balance" => (int)$data['total_balance']
            );
            array_push($payments_to_return, $array_to_push);
        }

        $data_to_return = array(
            "id" => $order_info[0]['id'],
            "date_order" => $order_info[0]['date_order'],
            "name_of_customer" => $order_info[0]['name_of_customer'],
            "title" => $order_info[0]['title'],
            "total_balance" => (int)$order_info[0]['total_balance'],
            "payment_period_distance" => (int)$order_info[0]['payment_period_distance'],
            "payment_per_period" => (int)$order_info[0]['payment_per_period'],
            "admin_charge" => (int)$order_info[0]['admin_charge'],
            "total_balance_paid" => (int)$order_info[0]['total_balance_paid'],
            "day_percent" => $order_info[0]['day_percent'],
            "day_remaining" => $order_info[0]['day_remaining'],
            "total_balance_percent" => (int)$order_info[0]['total_balance_percent'],
            "total_payments_count" => (int)$order_info[0]['total_payments_count'],
            "payments" => $payments_to_return
        );
        return $data_to_return;
    }

    /**
     * Get order dashboard by id_prder.
     *
     * @param $id number.
     * @return array(
     * "day_percent": => number,
     * "day_remaining": => number,
     * "total_balance_percent": => number,
     * "total_payments_count": => number,
     * "date_order": => string,
     * "name_of_customer": => string,
     * "title": => string,
     * "total_balance": => number,
     * "total_balance_paid": => number,
     * "admin_charge": => number,
     * "payment_period_distance" => number,
     * "payments": => [
     * {
     * "id": => string,
     * "order_number": => number,
     * "date_paid": => string,
     * "balance": => number
     * }).
     */

    public function get_summary_orders()
    {
        $payment_model = new Binhusenstore_payment_model();

        $summary_order_table = "binhusenstore_order_summary";
        $orders = $this->database->select_from($summary_order_table)->fetchAll(PDO::FETCH_ASSOC);
        if (count($orders) == 0) return array();

        $result = array();

        foreach ($orders as $order) {
            // get phone here
            $id_order = $order['id'];
            $phone = $this->phone_by_order_id($id_order);

            $array_to_push = array(
                "phone" => $phone,
                "message" => "Hallo kak " . $order['name_of_customer']
                    . ",\n\nPesan ini dikirim karena kakak memiliki dana yang disimpan diplatform kami dengan Id pesanan " . $id_order
                    . ", Sistem kami mencatat total dana yang telah tersimpan adalah sebesar Rp*" . number_format($order['total_balance_paid'], 0, ",", ".")
                    . "*.\n\nData ini telah diverifikasi secara otomatis oleh sistem dan merupakan akumulasi dari seluruh transaksi Anda hingga tanggal " . date('d-M-Y')
                    . ".\n\nTerima kasih atas kepercayaan Anda dalam menggunakan layanan kami.\n\n"
                    . "Hormat kami,\n\n"
                    . "*Binhusenstore*\n> Pesan dibuat oleh sistem"
            );

            array_push($result, $array_to_push);
        }

        // $data_to_return = array(
        //     "id" => $order_info[0]['id'],
        //     "date_order" => $order_info[0]['date_order'],
        //     "name_of_customer" => $order_info[0]['name_of_customer'],
        //     "title" => $order_info[0]['title'],
        //     "total_balance" => (int)$order_info[0]['total_balance'],
        //     "payment_period_distance" => (int)$order_info[0]['payment_period_distance'],
        //     "payment_per_period" => (int)$order_info[0]['payment_per_period'],
        //     "admin_charge" => (int)$order_info[0]['admin_charge'],
        //     "total_balance_paid" => (int)$order_info[0]['total_balance_paid'],
        //     "day_percent" => $order_info[0]['day_percent'],
        //     "day_remaining" => $order_info[0]['day_remaining'],
        //     "total_balance_percent" => (int)$order_info[0]['total_balance_percent'],
        //     "total_payments_count" => (int)$order_info[0]['total_payments_count'],
        //     "payments" => $payments_to_return
        // );

        return $result;
    }

    public function get_order_by_id($id)
    {
        if (substr($id, 0, 1) !== 'G' && substr($id, 0, 1) !== 'O') return array();

        // Check the string length
        if (strlen($id) !== 9) return array();

        // Check if the rest of the characters are numbers
        for ($i = 1; $i < strlen($id); $i++) {
            if (!is_numeric($id[$i])) {
                return array();
            }
        }

        $is_group_order = substr($id, 0, 1) === 'G';

        $result = array();
        if ($is_group_order) {
            $get_orders = $this->database->select_where($this->table, 'id_group', $id)->fetchAll(PDO::FETCH_ASSOC);
            if (count($get_orders)) {

                $array_to_push = array(
                    "date_order" => "",
                    "id_group" => "",
                    "is_group" => true,
                    "id_product" => "",
                    "name_of_customer" => "",
                    "sent" => "",
                    "titles_group" => array(),
                    "total_balance" => 0,
                    "admin_charge" => 0
                );

                foreach ($get_orders as $order) {
                    //  check date order, is date order older than date pushed
                    // if yes push the date
                    $is_date_order_older = $order['date_order'] > $array_to_push['date_order'];
                    if ($is_date_order_older) {
                        $array_to_push['date_order'] = $order['date_order'];
                    }

                    $array_to_push['id_group'] = $order['id_group'];
                    $array_to_push['id_product'] = $order['id_product'];
                    $array_to_push['sent'] = $order['sent'];

                    // sum the total balance and admin charge
                    $array_to_push['total_balance'] += $order['total_balance'];
                    $array_to_push['admin_charge'] += $order['admin_charge'];

                    // title order should be array which contain both order title
                    array_push($array_to_push['titles_group'], $order['title']);
                }

                array_push($result, $array_to_push);
            }
        } else $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function get_order_by_id_product($id_product)
    {

        $result = $this->database->select_where($this->table, 'id_product', $id_product)->fetchAll(PDO::FETCH_ASSOC);;
        if ($this->database->is_error === null) return $result;
        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_order_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if ($this->database->is_error === null) {

            if ($result === 0) return $this->database->is_id_exists($this->table, $id);
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
        $is_order_exists = count($retrieve_order) > 0;
        if (!$is_order_exists) return 0;

        $phone_decrypted =  decrypt_string($retrieve_order[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);
        $is_phone_matched = $phone_decrypted == $phone;
        if (!$is_phone_matched) return "Nomor telfon pengguna tidak sesuai dengan database!";

        // everything is oke to continue :)
        // first thing, set data to insert
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
            // remove order and the payments
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

        return false;
    }

    public function merge_order_as_group($id_order_1, $id_order_2)
    {

        // in this function, we're just add information in the table binhusenstore_orders.is_group and binhusenstore_orders.id_group
        // also in the table binhusenstore_payments.id_order_group

        $order_1 = $this->database->select_where($this->table, 'id', $id_order_1)->fetchAll(PDO::FETCH_ASSOC);
        $order_2 = $this->database->select_where($this->table, 'id', $id_order_2)->fetchAll(PDO::FETCH_ASSOC);

        $is_order_exists = count($order_1) > 0 && count($order_2) > 0;
        if (!$is_order_exists) return 0;

        $phone_1_decrypted =  decrypt_string($order_1[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);
        $phone_2_decrypted =  decrypt_string($order_2[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);

        $is_phone_unmatched = $phone_1_decrypted != $phone_2_decrypted;
        if ($is_phone_unmatched) return "Nomor handphone pemesan tidak sama";

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

    function unmerge_order_group($id_group, $phone)
    {
        // the phone number should be matched
        // update binhusenstore_orders.is_group and binhusenstore_orders.id_group = "" where id_group = $id_group
        // also in the table binhusenstore_payments.id_order_group = ""

        $order = $this->database->select_where($this->table, 'id_group', $id_group)->fetchAll(PDO::FETCH_ASSOC);

        $is_order_exists = count($order) > 0;
        if (!$is_order_exists) return 0;

        $phone_1_decrypted =  decrypt_string($order[0]['phone'], ENCRYPT_DECRYPT_PHONE_KEY);

        $is_phone_unmatched = $phone_1_decrypted != $phone;
        if ($is_phone_unmatched) return "Nomor handphone pemesan tidak sama";

        $data_order_to_update = array(
            'is_group' => 0,
            'id_group' => ""
        );

        $payment_model = new Binhusenstore_payment_model();

        $this->update_order_by_id($data_order_to_update, 'id_group', $id_group);
        return $payment_model->remove_id_group_payment_by_id_order($id_group);
    }
}
