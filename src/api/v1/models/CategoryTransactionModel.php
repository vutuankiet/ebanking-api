<?php

class CategoryTransactionModel extends DB
{
    public function getAllCateTransaction()
    {
        try {
            $sql = "SELECT * FROM category_transaction WHERE state = 1;";
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

    public function getCateTransactionById($id)
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

    public function Add($name_transaction, $description, $fee_transaction): bool
    {
        try {
            $sql = "INSERT INTO category_transaction(name_transaction, description, fee_transaction) VALUE('$name_transaction', '$description', $fee_transaction);";
            return $this->executeUpdateAndInsert($sql);
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $name_transaction, $description, $fee_transaction)
    {
        try {
            $checkExit = $this->getCateTransactionById($id);
            if ($checkExit !== null) {
                $checkName = $name_transaction === "" ? "name_transaction" : "'$name_transaction'";
                $checkDescription = $description === "" ? "description" : "'$description'";
                $checkFee_transaction = $fee_transaction === "" ? "fee_transaction" : "$fee_transaction";
                $sql = "UPDATE category_transaction SET name_transaction = $checkName, description = $checkDescription, fee_transaction = $checkFee_transaction WHERE id_category_transaction = $id;";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Category Transaction not found by id : $id", "Failed!");
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
            if ($this->getCateTransactionById($id) !== null) {
                $sql = "UPDATE category_transaction SET state = 0 WHERE id_category_transaction = $id;";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No Category Transaction found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }
}