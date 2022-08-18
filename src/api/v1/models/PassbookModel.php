<?php

class PassbookModel extends DB
{

    public function getAllPassbook()
    {
        try {
            $sql = "SELECT * FROM passbook WHERE state = 1;";
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

    public function getPassbookById($id)
    {
        try {
            $sql = "SELECT * FROM passbook WHERE id_passbook = $id AND state = 1;";
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

    public function CheckCategoryPassbookIDHasExit($id_category_passbook)
    {
        try {
            $sql = "SELECT * FROM category_passbook WHERE id_category_passbook = '$id_category_passbook';";
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

    public function CheckPassbookHasExit($id_customer)
    {
        try {
            $sql = "SELECT * FROM passbook WHERE id_customer = '$id_customer';";
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

    public function Add($id_customer, $money, $id_category_passbook): bool
    {
        try {
            if ($this->CheckCustomerIDHasExit($id_customer) && $this->CheckCategoryPassbookIDHasExit($id_category_passbook)) {
                $sql_money = "SELECT money FROM customer WHERE id_person = '$id_customer';";
                $balance = $this->executeSelect($sql_money);
                $money_customer = $balance[0]["money"] ?? 0;
                if($money_customer >= $money){
                    $money_last_customer = $money_customer - $money;
                    $sql = "INSERT INTO passbook(id_customer, money, id_category_passbook, updated_at) VALUE($id_customer, $money, $id_category_passbook, null);";
                    $sql_customer = "UPDATE `customer` SET `money` = $money_last_customer, `updated_at` = CURRENT_TIMESTAMP() WHERE `id_person` = $id_customer;";
                    return $this->executeUpdateAndInsert($sql)&&$this->executeUpdateAndInsert($sql_customer);
                }else{
                    echo json_encode(array("query_err" => true, "err_detail" => "Account not enough money!", "result" => []));
                    die();
                }

            }
            echo json_encode(array("query_err" => true, "err_detail" => "Customer does not exit or category passbook!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $id_customer, $money, $id_category_passbook)
    {
        try {
            $checkExit = $this->getPassbookById($id);
            if ($checkExit !== null) {
                $checkIdCustomer = $id_customer === "" ? "id_customer" : "$id_customer";
                $checkMoney = $money === "" ? "money" : "$money";
                $checkCategoryPassbook = $id_category_passbook === "" ? "id_category_passbook" : "$id_category_passbook";
                $sql = "UPDATE passbook SET id_customer = $checkIdCustomer, money = $checkMoney, id_category_passbook = $checkCategoryPassbook, updated_at = CURRENT_TIMESTAMP() WHERE id_passbook = $id;";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Passbook not found by id : $id", "Failed!");
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
            if ($this->getPassbookById($id) !== null) {
                $sql = "UPDATE passbook SET state = 0 WHERE id_passbook = $id;";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No passbook found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function getPassbookByCustomer($token)
    {
        try {
//            $sql = "SELECT * FROM passbook WHERE id_customer LIKE '" . "%" . $id_customer . "%' AND state = 1;";
            $sql = "SELECT passbook.id_passbook, passbook.id_customer, passbook.money, passbook.id_category_passbook,passbook.created_at,passbook.updated_at,passbook.state FROM passbook  JOIN `customer` ON passbook.id_customer= customer.id_person
  JOIN `account` ON customer.phone=`account`.phone
   WHERE token LIKE '" . "%" . $token . "%' AND passbook.state = 1;";
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