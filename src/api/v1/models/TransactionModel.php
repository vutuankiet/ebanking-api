<?php

class TransactionModel extends DB
{
    public function getCategoryTransactionById($id)
    {
        try {
            $sql = "SELECT * FROM category_transaction WHERE id_category_transaction = $id AND state = 1;";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
            return null;
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function CheckCustomerIDHasExit($id_customer)
    {
        try {
            $sql = "SELECT * FROM customer WHERE id_person = $id_customer AND state = 1;";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return true;
            }
            return false;
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function AddTransaction($id, $money, $from)
    {
        try {
            if ($this->CheckCustomerIDHasExit($from) && $this->getCategoryTransactionById($id)) {
                $status = "Thành Công";
                $sql = "INSERT INTO history_transaction(`money`, `from`, `id_category_transaction`, `status`, `updated_at`) VALUES($money, $from, $id, '$status', null);";
                return $this->executeUpdateAndInsert($sql);
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Customer does not exit!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }
}