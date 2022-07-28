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

    public function Add($pin_code, $status, $id_customer)
    {
        try {
            $length = 10;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $id = $randomString;
            $status = "Đã kích hoạt";
            $sql = "INSERT INTO card(id_card,pin,status,state,id_customer) VALUE('$id','$pin_code','$status',1,'$id_customer')";
            $result = $this->executeUpdateAndInsert($sql);
            if ($result) {
                return array("id" => $id, "result" => $result);
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
                $sql = "UPDATE card SET pin = $checkPin, status = $checkStatus WHERE id_card = '$id'";
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

    public function getCardByCustomer($id_customer)
    {
        try {
            $sql = "SELECT card.id_card,card.pin,card.status,card.state from card JOIN customer ON card.id_customer = customer.id_person WHERE customer.state = 1 AND card.id_customer = $id_customer;";
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