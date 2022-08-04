<?php

class HistoryTransactionModel extends DB
{
    public function getAllHistoryTransaction()
    {
        try {
            $sql = "SELECT * FROM history_transaction WHERE state = 1;";
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

    public function Add($id_category_transaction, $money, $id_customer, $part_transaction, $status): bool
    {
        try {
            if ($this->CheckCustomerIDHasExit($id_customer) && $this->CheckCategoryTransactionIDHasExit($id_category_transaction)) {
                $sql = "INSERT INTO history_transaction(id_category_transaction, money, id_customer, part_transaction, status, updated_at) VALUE($id_category_transaction, $money, $id_customer, '$part_transaction', '$status', null);";
                print_r($sql);
                return $this->executeUpdateAndInsert($sql);
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Customer does not exit or category transaction!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $id_category_transaction, $money, $id_customer, $part_transaction, $status)
    {
        try {
            $checkExit = $this->getHistoryTransactionById($id);
            if ($checkExit !== null) {
                $checkIdCategoryTransaction = $id_category_transaction === "" ? "id_category_transaction" : "$id_category_transaction";
                $checkMoney = $money === "" ? "money" : "$money";
                $checkIdCustomer = $id_customer === "" ? "id_customer" : "$id_customer";
                $checkPartTransaction = $part_transaction === "" ? "part_transaction" : "'$part_transaction'";
                $checkPartStatus = $status === "" ? "status" : "'$status'";
                $sql = "UPDATE history_transaction SET id_category_transaction = $checkIdCategoryTransaction, money = $checkMoney, id_customer = $checkIdCustomer, part_transaction = $checkPartTransaction, status = $checkPartStatus , updated_at = CURRENT_TIMESTAMP() WHERE id_transaction = $id;";
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