<?php

class CustomerModel extends DB
{

    public function getAllCustomer()
    {
        try {
            $sql = "SELECT * FROM customer WHERE state = 1;";
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

    public function changePassword($password, $id)
    {
        $result = $this->getCustomerById($id);
        if ($result !== null) {
            $phone = $result[0]["phone"];
            $sql = "UPDATE account SET password = $password WHERE phone = $phone;";
            return $this->executeUpdateAndInsert($sql);
        }
        $this->jsonResponse(true, "no user found by id : $id", "Failed!");
        die();
    }

    public function getCustomerById($id)
    {
        try {
            $sql = "SELECT * FROM customer WHERE id_person = $id AND state = 1;";
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

    public function getPhoneByCustomerId($id)
    {
        try {
            $sql = "SELECT * FROM customer WHERE id_person = $id";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return $result[0]["phone"];
            }
            return -1;
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function CheckCustomerHasExit($citizen_identity_card, $phone, $mail)
    {
        try {
            $sql = "SELECT * FROM customer WHERE phone ='$phone' OR mail ='$mail' OR citizen_identity_card ='$citizen_identity_card';";
            $result = $this->executeSelect($sql);
            return (is_array($result) && count($result) > 0);
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Add($name, $password, $citizen_identity_card, $phone, $mail, $address, $age, $money, $id_card, $id_branch): bool
    {
        try {
            if (!$this->CheckCustomerHasExit($citizen_identity_card, $phone, $mail)) {
                $rs = $this->SignUp($phone, $password);
                if ($rs) {
                    $sql = "INSERT INTO customer(name, citizen_identity_card, phone, mail, address, age, money, id_card, id_branch) VALUE('$name','$citizen_identity_card','$phone','$mail','$address','$age','$money','$id_card','$id_branch');";
                    $result = $this->executeUpdateAndInsert($sql);
                    if ($result) {
                        $sql_select = "SELECT id_person FROM customer WHERE phone = '$phone';";
                        $customer_id = $this->executeSelect($sql_select)[0]["id_person"];
                        $this->jsonResponse(false, "", "new customer id :  $customer_id");
                    }
                }
                die();
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Customer citizen_identity_card or phone or mail has exit!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function getBalanceById($id)
    {
        $sql = "SELECT money FROM customer WHERE id_person = $id;";
        $balance = $this->executeSelect($sql);
        return $balance[0]["money"] ?? 0;
    }

    public function updatePhone($oldPhone, $phone)
    {
        $sql = "UPDATE account SET phone = $phone WHERE phone = '$oldPhone';";
        return $this->executeUpdateAndInsert($sql);
    }

    public function Update($id, $name, $citizen_identity_card, $phone, $mail, $address, $age, $money, $id_card, $id_branch)
    {
        try {
            $checkExit = $this->getCustomerById($id);
            if ($checkExit !== null) {
                $checkName = $name === "" ? "name" : "'$name'";
                $checkCitizenIdentityCard = $citizen_identity_card === "" ? "citizen_identity_card" : "'$citizen_identity_card'";
                $checkPhone = $phone === "" ? "phone" : "'$phone'";
                $checkMail = $mail === "" ? "mail" : "'$mail'";
                $checkAddress = $address === "" ? "address" : "'$address'";
                $checkAge = $age === "" ? "age" : "$age";
                $checkMoney = $money === "" ? "money" : "$money";
                $checkIdCard = $id_card === "" ? "id_card" : "'$id_card'";
                $checkIdBranch = $id_branch === "" ? "id_branch" : "'$id_branch'";
                $oldPhone = $this->getPhoneByCustomerId($id);
                if ($oldPhone != -1) {
                    $this->updatePhone($oldPhone, $checkPhone);
                }
                $sql = "UPDATE customer SET name = $checkName, citizen_identity_card = $checkCitizenIdentityCard, phone = $checkPhone, mail = $checkMail, address = $checkAddress, age = $checkAge, money = $checkMoney, id_card = $checkIdCard, id_branch = $checkIdBranch WHERE id_person = $id;";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Customer not found by id : $id", "Failed!");
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
            if ($this->getCustomerById($id) !== null) {
                $sql = "UPDATE customer SET state = 0 WHERE id_person = $id;";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No customer found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function checkPhoneHasExit($phone)
    {
        try {
            $sql = "SELECT * FROM account WHERE  phone = '$phone';";
            $result = $this->executeSelect($sql);
            return (is_array($result) && count($result) > 0);
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function SignUp($phone, $password)
    {
        try {
            if (!$this->checkPhoneHasExit($phone)) {
                $sql = "INSERT INTO account(phone, password) VALUE('$phone', '$password');";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Phone has exit!", "Failed!");
            }
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
        }
    }

    public function SignIn($phone, $password)
    {
        try {
            $sql = "SELECT * FROM account WHERE phone = '$phone' and password = '$password'";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                $token = base64_encode($phone . $password . date("Y:m:d"));
                $sql_insert_token = "UPDATE account SET token = '$token' WHERE phone = '$phone';";
                $res = $this->executeUpdateAndInsert($sql_insert_token);
                if (!$res) {
                    $this->jsonResponse(true, "Something err when set token!", "Failed!");
                    die();
                }
                $this->jsonResponse(false, "", ["token" => $token]);
                die();
            };
            return false;
        } catch (Exception $ex) {
            $this->trigger_response($ex);
        }
    }

    public function checkPassword($password, $id)
    {
        $customer = $this->getCustomerById($id);
        if ($customer !== null) {
            $phone = $customer[0]["phone"];
            $sql = "SELECT * FROM account WHERE phone = $phone AND password = $password";
            $select_result = $this->executeSelect($sql);
            return (is_array($select_result) && count($select_result) > 0);
        }
        $this->jsonResponse(true, "No customer found by id :  $id", "Failed!");
        die();
    }

    public function getCustomerByBranch($id_branch)
    {
        try {
            $sql = "SELECT * FROM customer WHERE id_branch LIKE '" . "%" . $id_branch . "%' and state = 1;";
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