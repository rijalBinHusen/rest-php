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
        $result  = $this->database->select_where($this->table, 'id_order', $id_order)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) {

            return $result;
        }

        $this->is_success = $this->database->is_error;
    }

    public function get_payment_by_id($id)
    {

        $result = $this->database->select_where($this->table, 'id', $id)->fetchAll(PDO::FETCH_ASSOC);

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

            if ($result === 0) {

                $query = "SELECT EXISTS(SELECT id FROM $this->table WHERE id = '$id')";
                return $this->database->sqlQuery($query)->fetchColumn();
            }

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

        $is_phone_matched = $phone_order === $phone;
        
        if(!$is_phone_matched) return "Id order atau nomor telfon tidak ditemukan";

        $query_payment_by_id_order = "SELECT id, balance, date_payment FROM $this->table WHERE id_order = '$id_order' AND is_paid = '0' ORDER BY date_payment";
        $retrieve_all_payment = $this->database->sqlQuery($query_payment_by_id_order)->fetchAll(PDO::FETCH_ASSOC);

        if (count($retrieve_all_payment) === 0) return 0;
        

        $total_balance = 0;
        foreach ($retrieve_all_payment as $value) {
            $total_balance += $value['balance'];
        }

        if ($payment > $total_balance) return "Pembayaran melebihi tagihan";

        $payment_left = $payment;

        for ($i = 0; $i < $payment; $i++) {

            $is_the_last_bill = $i >= (count($retrieve_all_payment) - 1);
            $payment_index = $i;

            if ($is_the_last_bill) {

                $payment_index = count($retrieve_all_payment) - 1;
            }

            $payment_id = $retrieve_all_payment[$payment_index]['id'];
            $payment_balance = $retrieve_all_payment[$payment_index]['balance'];

            if ($payment_left === 0) return true;

            if ($payment_left < 0) {

                if ($is_the_last_bill) {

                    $the_payment_date = $retrieve_all_payment[$i - 1]['date_payment'];
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

    public function sum_balance()
    {

        $query_sum_balance = "SELECT SUM(balance) as total_balance FROM $this->table WHERE is_paid = 1";
        $sum_balance = $this->database->sqlQuery($query_sum_balance)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) return $sum_balance[0]['total_balance'];

        $this->is_success = $this->database->is_error;
    }

    public function move_payment_to_archive($id_order)
    {

        $retrieve_payments = $this->get_payments($id_order);

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
        )";

        if($is_limiter_oke) $query_payment_group_by_id_order = $query_payment_group_by_id_order . " LIMIT $limit";

        $result = $this->database->sqlQuery($query_payment_group_by_id_order)->fetchAll(PDO::FETCH_ASSOC);

        if ($this->database->is_error === null) return $result;

        $this->is_success = $this->database->is_error;
        return array();
    }
}
