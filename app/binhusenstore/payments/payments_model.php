<?php
require_once(__DIR__ . '/../../../utils/database.php');
require_once(__DIR__ . '/../orders/order_model.php');
require_once(__DIR__ . '/../../google/spreadsheet.php');

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
        $order_model = new Binhusenstore_order_model();
        $order_summary = $order_model->get_order_dashboard_by_id($id_order);
        if (count($order_summary) === 0) return array();

        $payment_remaining = $order_summary['total_balance'] - $order_summary['total_balance_paid'];
        if ($balance > $payment_remaining) return "Pembayaran melebihi tagihan";
        $data_to_insert = array(
            'date_payment' => $date_payment,
            'id_order' => $id_order,
            'id_order_group' => $id_order_group,
            'balance' => $balance,
            'is_paid' => 0,
            'date_paid' => true,
        );

        $this->database->insert($this->table, $data_to_insert);

        if ($this->database->is_error === null) {

            return $this->database->getMaxId($this->table);
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payments($id_order)
    {
        // if (substr($id_order, 0, 1) !== 'G' && substr($id_order, 0, 1) !== 'O') return array();
        if (substr($id_order, 0, 1) !== 'O') return array();

        // Check the string length
        if (strlen($id_order) !== 9) return array();

        // Check if the rest of the characters are numbers
        for ($i = 1; $i < strlen($id_order); $i++) {
            if (!is_numeric($id_order[$i])) {
                return array();
            }
        }

        // $is_group_order = substr($id_order, 0, 1) === 'G';
        // if ($is_group_order) return $this->get_payments_by_id_order_group($id_order);
        $order_model = new Binhusenstore_order_model();
        $order_summary = $order_model->get_order_dashboard_by_id($id_order);

        if (count($order_summary) === 0) return array();

        $result_payments  = $this->database->select_where($this->table, 'id_order', $id_order, 'date_payment')->fetchAll(PDO::FETCH_ASSOC);
        $last_payment = "";
        if (count($result_payments) === 0) $last_payment = new DateTime($order_summary['date_order']);
        else
            $last_payment = new DateTime($result_payments[count($result_payments) - 1]['date_payment']);

        while ($i = $order_summary['total_balance_paid'] <= $order_summary['total_balance']) {
            // $last_payment as YY-MM-DD
            $date_payment = date('Y-m-d', strtotime($last_payment->format('Y-m-d')));
            $balance = $order_summary['payment_per_period'];

            $array_to_push = array(
                'id' => "",
                'date_payment' => $date_payment,
                'date_paid' => "",
                'id_order' => $id_order,
                'balance' => $balance,
                'is_paid' => false
            );
            array_push($result_payments, $array_to_push);
            $last_payment->modify("+ " . $order_summary['payment_period_distance'] . " week");
            $i += $balance;
        }

        if ($this->database->is_error === null) {

            $data_type_converted = $this->convert_data_type($result_payments);
            return $data_type_converted;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_paid_and_order_data_by_date_payment_desc($id_order)
    {
        if (substr($id_order, 0, 1) !== 'O') return array();
        // Check the string length
        if (strlen($id_order) !== 9) return array();
        // Check if the rest of the characters are numbers
        for ($i = 1; $i < strlen($id_order); $i++) {
            if (!is_numeric($id_order[$i])) {
                return array();
            }
        }

        $query = "SELECT date_paid, SUM(balance) as total_balance FROM binhusenstore_payments WHERE is_paid=1 AND id_order=:id_order GROUP BY date_paid ORDER BY date_paid DESC";
        $row = $this->database->custom_query_return_prepare($query);
        $row->bindValue(":id_order", $id_order, PDO::PARAM_STR);
        $row->execute();
        $result = $row->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payments_by_id_order_group($id_order_group)
    {
        $result  = $this->database->select_where($this->table, 'id_order_group', $id_order_group, 'date_payment')->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            $data_type_converted = $this->convert_data_type($result);
            return $data_type_converted;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payment_by_id($id)
    {

        $result = $this->database->select_where('order_payments', 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result[0];
        }

        $this->is_success = $this->database->is_error;
        return array();
    }

    public function update_payment_by_id(array $data, $where, $id)
    {

        $result = $this->database->update($this->table, $data, $where, $id);

        if ($this->database->is_error === null) return $result;
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

    // public function mark_payment_as_paid_by_id_order_or_id_group($id_order, $date_paid, $payment, $phone)
    // {

    //     if (substr($id_order, 0, 1) !== 'G' && substr($id_order, 0, 1) !== 'O') return array();

    //     // Check the string length
    //     if (strlen($id_order) !== 9) return array();

    //     // Check if the rest of the characters are numbers
    //     for ($i = 1; $i < strlen($id_order); $i++) {
    //         if (!is_numeric($id_order[$i])) {
    //             return array();
    //         }
    //     }

    //     // retrieve order by id || order by id group
    //     // retrieve summary payments by id || by id group
    //     // balance_remaining = order.total_balance - payments.total_balance
    //     // if($payment > balance_remaining) return "Payment more than bill"
    //     // else add payment based on date now

    //     $is_group_order = substr($id_order, 0, 1) === 'G';

    //     $where_s = "";
    //     if ($is_group_order) $where_s = array('id_order_group' => $id_order, 'is_paid' => '0');
    //     else $where_s = array('id_order' => $id_order, 'is_paid' => '0');

    //     $retrieve_payments = $this->database->select_where_s($this->table, $where_s, "date_payment")->fetchAll(PDO::FETCH_ASSOC);
    //     if (count($retrieve_payments) === 0) return 0;

    //     $order_model = new Binhusenstore_order_model();

    //     // find order by order_id and phone
    //     $phone_order = $order_model->phone_by_order_id($retrieve_payments[0]['id_order']);
    //     $is_phone_not_matched = $phone_order != $phone;
    //     if ($is_phone_not_matched) return "Id order atau nomor telfon tidak ditemukan";

    //     $total_balance = 0;
    //     foreach ($retrieve_payments as $value) {
    //         $total_balance += $value['balance'];
    //     }

    //     if ($payment > $total_balance) return "Pembayaran melebihi tagihan";
    //     $mark_as_paid = $this->mark_payment_as_paid($retrieve_payments, $payment, $date_paid);
    //     $this->append_payment_to_google_spreadsheet($date_paid, $payment, $id_order);
    //     return $mark_as_paid;
    // }
    // public function mark_payment_as_paid_by_id_order_or_id_group($id_order, $date_paid, $payment, $phone)
    // {

    //     if (substr($id_order, 0, 1) !== 'G' && substr($id_order, 0, 1) !== 'O') return array();

    //     // Check the string length
    //     if (strlen($id_order) !== 9) return array();

    //     // Check if the rest of the characters are numbers
    //     for ($i = 1; $i < strlen($id_order); $i++) {
    //         if (!is_numeric($id_order[$i])) {
    //             return array();
    //         }
    //     }

    //     $is_group_order = substr($id_order, 0, 1) === 'G';

    //     $where_s = "";
    //     if ($is_group_order) $where_s = array('id_order_group' => $id_order, 'is_paid' => '0');
    //     else $where_s = array('id_order' => $id_order, 'is_paid' => '0');

    //     $retrieve_payments = $this->database->select_where_s($this->table, $where_s, "date_payment")->fetchAll(PDO::FETCH_ASSOC);
    //     if (count($retrieve_payments) === 0) return 0;

    //     $order_model = new Binhusenstore_order_model();

    //     // find order by order_id and phone
    //     $phone_order = $order_model->phone_by_order_id($retrieve_payments[0]['id_order']);
    //     $is_phone_not_matched = $phone_order != $phone;
    //     if ($is_phone_not_matched) return "Id order atau nomor telfon tidak ditemukan";

    //     $total_balance = 0;
    //     foreach ($retrieve_payments as $value) {
    //         $total_balance += $value['balance'];
    //     }

    //     if ($payment > $total_balance) return "Pembayaran melebihi tagihan";
    //     $mark_as_paid = $this->mark_payment_as_paid($retrieve_payments, $payment, $date_paid);
    //     $this->append_payment_to_google_spreadsheet($date_paid, $payment, $id_order);
    //     return $mark_as_paid;
    // }

    // private function mark_payment_as_paid($payments_schedule, $payment, $date_paid)
    // {

    //     $payment_left = $payment;

    //     for ($i = 0; $i < $payment; $i++) {

    //         $is_the_last_bill = $i >= (count($payments_schedule) - 1);
    //         $payment_index = $i;

    //         if ($is_the_last_bill) $payment_index = count($payments_schedule) - 1;

    //         $payment_id = $payments_schedule[$payment_index]['id'];
    //         $payment_balance = $payments_schedule[$payment_index]['balance'];

    //         if ($payment_left === 0) return true;

    //         if ($payment_left < 0) {

    //             if ($is_the_last_bill) {

    //                 $the_payment_date = $payments_schedule[$i - 1]['date_payment'];
    //                 $id_order = $payments_schedule[$i - 1]['id_order'];
    //                 $this->append_payment($the_payment_date, $id_order, (-$payment_left), false);
    //             } else {

    //                 $data_to_update = array('balance' => $payment_balance + (-$payment_left));
    //                 $this->update_payment_by_id($data_to_update, 'id', $payment_id);
    //             }

    //             return true;
    //         }

    //         $is_payment_more_than_bill = $payment_left >= $payment_balance;
    //         // 1000 - 900 = +100;

    //         if ($is_payment_more_than_bill) {

    //             $data_to_update = array(
    //                 'date_paid' => $date_paid,
    //                 'balance' => $payment_left,
    //                 'is_paid' => true
    //             );

    //             $this->update_payment_by_id($data_to_update, 'id', $payment_id);
    //         } else {

    //             $data_to_update = array(
    //                 'balance' => $payment_left,
    //                 'is_paid' => true,
    //                 'date_paid' => $date_paid
    //             );

    //             $this->update_payment_by_id($data_to_update, 'id', $payment_id);
    //         }

    //         $payment_left = $payment_balance - $payment_left; // 1000 - 900= +100
    //     }

    //     $this->is_success = $this->database->is_error;

    //     return true;
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
                'date_paid' => $payment['date_paid'],
                'id_order' => $payment['id_order'],
                'balance' => (int)$payment['balance'],
                'is_paid' => boolval($payment['is_paid'])
            );

            array_push($result, $array_to_push);
        }

        return $result;
    }

    private function append_payment_to_google_spreadsheet($date_paid, $payment, $id_order)
    {

        $date_to_push  = date("m-d-Y", strtotime($date_paid));
        $values_to_append = [
            [
                $date_to_push,
                (int)$payment,
                "",
                "",
                $id_order
            ]
        ];

        $spreadsheetId = PAYMENT_SPREADSHEET_ID;
        $sSOperation = new Google_sheet_operation();
        $sSOperation->append_data_to_spreadsheet($spreadsheetId, "Binhusenstore!A:E", $values_to_append);
    }
}
