<?php

class CardModel extends DB
{
    public function getAllCard()
    {
        try {
            $sql = "SELECT * FROM card WHERE state = 1;";
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

    public function getCardById($id)
    {
        try {
            $sql = "SELECT * FROM card WHERE id_card = '$id' AND state = 1;";
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

    public function CheckCardIdentity($token)
    {
        try {
            $customerId = $this->getCustomerByToken($token)[0]["id_person"];

            $sql = "SELECT * FROM card WHERE id_customer = $customerId AND state = 1";
            $result = $this->executeSelect($sql);
            if (count($result) > 0 && is_array($result)) {
                return false;
            }
            return true;
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function CheckPinHasExit($pin)
    {
        try {
            $sql = "SELECT * FROM card WHERE pin = '$pin';";
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

    public function getCustomerByToken($token)
    {
        try {
            $sql = "SELECT customer.id_person, customer.name, customer.citizen_identity_card, customer.mail, customer.phone, customer.address, customer.age, customer.money,customer.created_at,customer.updated_at,customer.id_branch,customer.state FROM customer INNER JOIN account ON customer.phone=account.phone WHERE account.token = '$token' AND customer.state=1;";
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

    public function Add($pin_code, $status, $token)
    {
        try {
            if ($this->CheckCardIdentity($token)) {
                $length = 10;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $id = $randomString;
                $status = "Đã kích hoạt";
                $sql_customer = "SELECT customer.id_person as id_customers from customer JOIN account ON customer.phone = account.phone WHERE customer.state = 1 AND account.token = '$token'";
                $result1 = $this->executeSelect($sql_customer);
                $result2 = $result1[0];
                $id_customer = $result2['id_customers'];
                $sql = "INSERT INTO card(id_card,pin,status,state,id_customer,updated_at) VALUE('$id','$pin_code','$status',1,'$id_customer',null)";
                $result = $this->executeUpdateAndInsert($sql);
                if ($result) {
                    return array("id" => $id, "result" => $result);
                }
            } else {
                $this->jsonResponse(true, "Only Card Register For Customer!", []);
            }
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $pin_code, $status)
    {
        try {
            $checkExit = $this->getCardById($id);
            if ($checkExit !== null) {
                $checkPin = $pin_code === "" ? "pin" : "'$pin_code'";
                $checkStatus = $status === "" ? "status" : "'$status'";
                $sql = "UPDATE card SET pin = $checkPin, status = $checkStatus, updated_at = CURRENT_TIMESTAMP() WHERE id_card = '$id'";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Card not found by id : '$id'", "Failed!");
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
            if ($this->getCardById($id) !== null) {
                $sql = "UPDATE card SET state = 0 WHERE id_card = '$id';";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No card found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function getCardByCustomer($token)
    {
        try {
            $sql = "SELECT card.id_card,card.pin,card.status,card.state from customer JOIN card ON customer.id_person = card.id_customer JOIN account ON customer.phone = account.phone WHERE customer.state = 1 AND account.token = '$token';";
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