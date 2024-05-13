<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../orders/order_model.php');

class Binhusenstore_payment_model
{
    protected $database;
    var $table = "binhusenstore_payments";
    var $table_archived = "binhusenstore_payments_archived";
    var $is_success = true;

    function __construct()
    {

        $this->database = Query_builder::getInstance();
    }

    public function append_payment($date_payment, $id_order, $balance, $id_order_group)
    {

        $data_to_insert = array(
            'date_payment' => $date_payment,
            'id_order' => $id_order,
            'id_order_group' => $id_order_group,
            'balance' => $balance,
            'is_paid' => 0,
            'date_paid' => "",
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payments($id_order)
    {
        $result  = $this->database->select_where($this->table, 'id_order', $id_order, 'date_payment')->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $data_type_converted = $this->convert_data_type($result);
            return $data_type_converted;
        }

        $this->is_success = $this->database->is_error;
    }


    public function get_payments_by_id_order_group($id_order_group)
    {
        $result  = $this->database->select_where($this->table, 'id_order_group', $id_order_group)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $data_type_converted = $this->convert_data_type($result);
            return $data_type_converted;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payment_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id, 'date_payment')->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_payment_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if ($this->database->is_error === null) {        
    
            if($result === 0) return $this->database->is_id_exists($this->table, $id);
            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function remove_payment_by_id($id)
    {
        $result = $this->database->delete($this->table, 'id', $id);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function mark_payment_as_paid_by_id($id_order, $date_paid, $payment, $phone)
    {

        $order_model = new Binhusenstore_order_model();

        // find order by order_id and phone
        $phone_order = $order_model->phone_by_order_id($id_order);
        $is_phone_not_matched = $phone_order != $phone;
        if ($is_phone_not_matched) return "Id order atau nomor telfon tidak ditemukan";

        $where_s = array('id_order' => $id_order, 'is_paid' => '0');
        $retrieve_payments = $this->database->select_where_s($this->table, $where_s, "date_payment")->fetchAll(PDO::FETCH_ASSOC);
        if (count($retrieve_payments) === 0) return 0;

        $total_balance = 0;
        foreach ($retrieve_payments as $value) {
            $total_balance += $value['balance'];
        }

        if ($payment > $total_balance) return "Pembayaran melebihi tagihan";
        $mark_as_paid = $this->mark_payment_as_paid($retrieve_payments, $payment, $date_paid);
        return $mark_as_paid;
    }

    public function mark_payment_as_paid_by_id_order_group($id_order_group, $date_paid, $payment, $phone)
    {
        $where_s = array('id_order_group' => $id_order_group, 'is_paid' => '0');
        $retrieve_payments = $this->database->select_where_s($this->table, $where_s, "date_payment")->fetchAll(PDO::FETCH_ASSOC);
        if (count($retrieve_payments) === 0) return 0;

        $id_order = $retrieve_payments[0]['id_order'];

        $order_model = new Binhusenstore_order_model();
        // find order by order_id and phone
        $phone_order = $order_model->phone_by_order_id($id_order);
        $is_phone_not_matched = $phone_order != $phone;
        if ($is_phone_not_matched) return "Id order atau nomor telfon tidak ditemukan";


        $total_balance = 0;
        foreach ($retrieve_payments as $value) {
            $total_balance += $value['balance'];
        }

        if ($payment > $total_balance) return "Pembayaran melebihi tagihan";
        $mark_as_paid = $this->mark_payment_as_paid($retrieve_payments, $payment, $date_paid);
        return $mark_as_paid;
    }

    private function mark_payment_as_paid($payments_schedule, $payment, $date_paid)
    {

        $payment_left = $payment;

        for ($i = 0; $i < $payment; $i++) {

            $is_the_last_bill = $i >= (count($payments_schedule) - 1);
            $payment_index = $i;

            if ($is_the_last_bill) $payment_index = count($payments_schedule) - 1;

            $payment_id = $payments_schedule[$payment_index]['id'];
            $payment_balance = $payments_schedule[$payment_index]['balance'];

            if ($payment_left === 0) return true;

            if ($payment_left < 0) {

                if ($is_the_last_bill) {

                    $the_payment_date = $payments_schedule[$i - 1]['date_payment'];
                    $id_order = $payments_schedule[$i - 1]['id_order'];
                    $this->append_payment($the_payment_date, $id_order, (-$payment_left), false);
                } else {

                    $data_to_update = array('balance' => $payment_balance + (-$payment_left));
                    $this->update_payment_by_id($data_to_update, 'id', $payment_id);
                }

                return true;
            }

            $is_payment_more_than_bill = $payment_left >= $payment_balance;
            // 1000 - 900 = +100;

            if ($is_payment_more_than_bill) {

                $data_to_update = array(
                    'date_paid' => $date_paid,
                    'is_paid' => true
                );

                $this->update_payment_by_id($data_to_update, 'id', $payment_id);
            } else {

                $data_to_update = array(
                    'balance' => $payment_left,
                    'is_paid' => true,
                    'date_paid' => $date_paid
                );

                $this->update_payment_by_id($data_to_update, 'id', $payment_id);
            }

            $payment_left = $payment_left - $payment_balance; // 1000 - 900= +100
        }

        $this->is_success = $this->database->is_error;

        return true;
    }

    // public function insert_payment_to_spreadsheet($id_payment, $id_order, $date_payment, $balance)
    // {

    //     $app_script_url = APP_SCRIPT_URL .  "?action=insert&id_payment=$id_payment&id_order=$id_order&date_payment=$date_payment&balance=$balance";
    //     $curl = curl_init();

    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => $app_script_url,
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "GET",
    //         CURLOPT_HTTPHEADER => [
    //             "Accept: */*",
    //             "User-Agent: Thunder Client (https://www.thunderclient.com)"
    //         ],
    //     ]);

    //     $response = curl_exec($curl);
    //     curl_close($curl);

    //     // debugger

    //     $myfile = fopen("debug.txt", "w") or die("Unable to open file!");
    //     fwrite($myfile, json_encode($response));
    //     fclose($myfile);
    // }

    public function sum_balance()
    {

        $query_sum_balance = "SELECT SUM(balance) as total_balance FROM $this->table WHERE is_paid = 1";
        $sum_balance = $this->database->sqlQuery($query_sum_balance)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) return $sum_balance[0]['total_balance'];

        $this->is_success = $this->database->is_error;
    }

    public function move_payment_to_archive($id_order)
    {

        $retrieve_payments  = $this->database->select_where($this->table, 'id_order', $id_order)->fetchAll(PDO::FETCH_ASSOC);
        $is_product_exists = count($retrieve_payments) > 0;

        if ($is_product_exists) {

            foreach ($retrieve_payments as $payment_value) {

                $data_to_insert = array(
                    'id' => $payment_value['id'],
                    'date_payment' => $payment_value['date_payment'],
                    'id_order' => $payment_value['id_order'],
                    'id_order_group' => $payment_value['id_order_group'],
                    'balance' => $payment_value['balance'],
                    'is_paid' => $payment_value['is_paid'],
                    'date_paid' => $payment_value['date_paid'],
                );

                $this->database->insert($this->table_archived, $data_to_insert);

                if ($this->database->is_error === null) {

                    $this->remove_payment_by_id($payment_value['id']);
                } else {

                    return $this->database->is_error;
                }
            }

            return true;
        }

        return "Payment not found";
    }

    public function retrieve_payment_group_by_id_order($limit)
    {

        $is_limiter_oke = is_numeric($limit) && $limit > 0;

        $query_payment_group_by_id_order = "SELECT date_payment, id_order, balance
        FROM binhusenstore_payments
        WHERE (id_order, date_payment) IN (
          SELECT id_order, MIN(date_payment)
          FROM binhusenstore_payments WHERE is_paid = 0 AND id_order_group = ''
          GROUP BY id_order
        ) AND date_payment <= CURRENT_DATE()";

        if ($is_limiter_oke) $query_payment_group_by_id_order = $query_payment_group_by_id_order . " LIMIT $limit";

        $result = $this->database->sqlQuery($query_payment_group_by_id_order)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) return $result;

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function add_id_group_payment_by_id_order($id_order, $id_order_group)
    {

        $data_to_update = array(
            'id_order_group' => $id_order_group
        );

        $result = $this->database->update($this->table, $data_to_update, 'id_order', $id_order);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function remove_id_group_payment_by_id_order($id_order_group)
    {

        $data_to_update = array('id_order_group' => "");
        $result = $this->database->update($this->table, $data_to_update, 'id_order_group', $id_order_group);

        if ($this->database->is_error === null) return $result;
        $this->is_success = $this->database->is_error;
    }

    private function convert_data_type($payments)
    {

        $result = array();
        // empty result
        if (count($payments) === 0) return $result;

        // mapping products
        foreach ($payments as $payment) {

            $array_to_push = array(
                'id' => $payment['id'],
                'date_payment' => $payment['date_payment'],
                'id_order' => $payment['id_order'],
                'balance' => (int)$payment['balance'],
                'is_paid' => boolval($payment['is_paid'])
            );

            array_push($result, $array_to_push);
        }

        return $result;
    }
}
