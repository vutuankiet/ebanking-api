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

    public function Add($id_customer, $money, $period, $interest_rate, $status, $description): bool
    {
        try {
            if (!$this->CheckPassbookHasExit($id_customer)) {
                if ($this->CheckCustomerIDHasExit($id_customer)) {
                    $withdrawaled_time = date('Y-m-d H:i:s', strtotime("now +$period months"));
                    $desc = "Sổ tiết kiệm có kỳ hạn $period tháng!";
                    $sql = "INSERT INTO passbook(id_customer, money, period, interest_rate, status, description,withdrawaled_at) VALUE($id_customer, $money, $period, $interest_rate, '$status', '$desc','$withdrawaled_time');";
                    return $this->executeUpdateAndInsert($sql);
                }
                echo json_encode(array("query_err" => true, "err_detail" => "Customer does not exit!", "result" => []));
                die();
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Customer has exit!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $id_customer, $money, $period, $interest_rate, $status, $description)
    {
        try {
            $checkExit = $this->getPassbookById($id);
            if ($checkExit !== null) {
                $checkIdCustomer = $id_customer === "" ? "id_customer" : "$id_customer";
                $checkMoney = $money === "" ? "money" : "$money";
                $checkPeriod = $period === "" ? "period" : "$period";
                $checkInterestRate = $interest_rate === "" ? "interest_rate" : "$interest_rate";
                $checkStatus = $status === "" ? "status" : "'$status'";
                $checkDescription = $description === "" ? "description" : "'$description'";
                $sql = "UPDATE passbook SET id_customer = $checkIdCustomer, money = $checkMoney, period = $checkPeriod, interest_rate = $checkInterestRate, status = $checkStatus, description = $checkDescription WHERE id_passbook = $id;";
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

    public function getPassbookByCustomer($id_customer)
    {
        try {
            $sql = "SELECT * FROM passbook WHERE id_customer LIKE '" . "%" . $id_customer . "%' AND state = 1;";
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