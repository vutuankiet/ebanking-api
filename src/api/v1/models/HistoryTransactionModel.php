<?php

class HistoryTransactionModel extends DB
{
    public function getAllHistoryTransaction()
    {
        try {
            $sql = "SELECT history_transaction.id_transaction,customer.name,customer.phone, history_transaction.money,category_transaction.name_transaction, category_transaction.description, category_transaction.fee_transaction, history_transaction.status, history_transaction.created_at FROM (( history_transaction INNER JOIN customer ON history_transaction.`from`=customer.id_person) INNER JOIN category_transaction ON history_transaction.id_category_transaction=category_transaction.id_category_transaction) WHERE history_transaction.state = 1;";
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

    public function getHistoryTransactionById($id)
    {
        try {
            $sql = "SELECT * FROM history_transaction WHERE id_transaction = $id AND state = 1;";
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
            $sql = "SELECT * FROM customer WHERE id_person = '$id_customer';";
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

    public function CheckCategoryTransactionIDHasExit($id_category_transaction)
    {
        try {
            $sql = "SELECT * FROM category_transaction WHERE id_category_transaction = '$id_category_transaction';";
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

    public function CheckHistoryTransactionHasExit($id_customer)
    {
        try {
            $sql = "SELECT * FROM history_transaction WHERE id_customer = '$id_customer';";
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

    public function Add($money, $from, $id_category_transaction): bool
    {
        try {
            if ($this->CheckCustomerIDHasExit($from)) {
                $status = "Thành Công";
                $sql = "INSERT INTO history_transaction(`money`, `from`, `id_category_transaction`, `status`, `updated_at`) VALUES($money, $from, $id_category_transaction, '$status', null);";
                return $this->executeUpdateAndInsert($sql);
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Customer does not exit!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $money, $from, $id_category_transaction, $status)
    {
        try {
            $checkExit = $this->getHistoryTransactionById($id);
            if ($checkExit !== null) {
                $checkMoney = $money === "" ? "money" : "$money";
                $checkFrom = $from === "" ? "from" : "$from";
                $checkIdCategoryTransaction = $id_category_transaction === "" ? "id_category_transaction" : "$id_category_transaction";
                $checkPartStatus = $status === "" ? "status" : "'$status'";
                $sql = "UPDATE history_transaction SET `money` = $checkMoney, `from` = $checkFrom, `id_category_transaction` = $checkIdCategoryTransaction, `status` = $checkPartStatus , `updated_at` = CURRENT_TIMESTAMP() WHERE id_transaction = $id;";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Transaction not found by id : $id", "Failed!");
                die();
            }
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function Remove($id)
    {
        try {
            if ($this->getHistoryTransactionById($id) !== null) {
                $sql = "UPDATE history_transaction SET state = 0 WHERE id_transaction = $id;";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No history transaction found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function getHistoryTransactionByCustomer($id_customer)
    {
        try {
            $sql = "SELECT * FROM history_transaction WHERE id_customer LIKE '" . "%" . $id_customer . "%' AND state = 1;";
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
}