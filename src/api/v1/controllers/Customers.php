<?php

class Customers extends Controller
{
    public function Index($id = null)
    {
        $model = $this->model("CustomerModel");

        if ($id === null) {
            //        get all customer function
            $customers = $model->getAllCustomer();
            if ($customers !== null) {
                $this->jsonResponse(false, "", $customers);
            } else {
                $this->jsonResponse(true, "no customer in server!", []);
            }
        } else {
//            get by id
            $customers = $model->getCustomerById($id);
            if ($customers !== null) {
                $this->jsonResponse(false, "", $customers);
            } else {
                $this->jsonResponse(true, "Not Found!", []);
                die();
            }
        }
    }

    public function GetCustomerByBranch($id_branch)
    {
        $model = $this->model("CustomerModel");
        $result = $model->getCustomerByBranch($id_branch);
        if ($result !== null) {
            echo json_encode(array("branch" => $id_branch, "query_err" => "false", "err_detail" => "", "results" => $result));
        } else {
            echo json_encode(array("branch" => $id_branch, "query_err" => "false", "err_detail" => "", "results" => [], "message" => "No branch found by keyword!"));
        }
    }

    public function RemoveById($id): void
    {
        if ($id === "") {
            echo json_encode(array("query_err" => true, "err_detail" => "Id incorrect!", "results" => []));
            die();
        }
        $this->connectModel("CustomerModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
    }

    public function SignUp($authInfo): void
    {
        $this->connectModel("CustomerModel");
        $phone = $authInfo["phone"] ?? "";
        $password = $authInfo["password"] ?? "";
        $isValid = true;
        if (trim($phone) === "" || trim($password) === "") {
            $isValid = false;
            if (trim($phone) === "") {
                $this->jsonResponse(true, "No Phone number found in body data!", "Failed!");
            } else {
                $this->jsonResponse(true, "No password found in body data!", "Failed!");
            }
        }

        if ($isValid) {
            $req = $this->model_->SignUp($phone, $password);
            if ($req) {
                $this->jsonResponse(false, "", "new user id : ");
            }
        }
        die();
    }

    public function SignIn($authInfo): void
    {
        $phone = $authInfo["phone"] ?? "";
        $password = $authInfo["password"] ?? "";
        $isValid = true;
        if (trim($phone) === "" || trim($password) === "") {
            $isValid = false;
            if (trim($phone) === "") {
                $this->jsonResponse(true, "No Phone number found in body data!", "Failed!");
            } else {
                $this->jsonResponse(true, "No password found in body data!", "Failed!");
            }
        }
        if ($isValid) {
            $this->connectModel("CustomerModel");
            $result = $this->model_->SignIn($phone, $password);
            if ($result) {

                $this->jsonResponse(false, "", "Success!");
            } else {
                $this->jsonResponse(true, "Number phone or password incorrect!", "Failed!");
            }
        } else {
            $this->jsonResponse(true, "Data not valid!", "Failed!");
        }
        die();
    }

    public function UpdateById($customer)
    {
        if (is_array($customer) && count($customer) > 0) {
            $this->connectModel("CustomerModel");
            $name = $customer["name"] ?? "";
            $citizen_identity_card = $customer["citizen_identity_card"] ?? "";
            $phone = $customer["phone"] ?? "";
            $mail = $customer["mail"] ?? "";
            $address = $customer["address"] ?? "";
            $age = $customer["age"] ?? "";
            $money = $customer["money"] ?? "";
            $id_card = $customer["id_card"] ?? "";
            $id_branch = $customer["id_branch"] ?? "";
            $id = $customer["id"] ?? "";
            $result = $this->model_->Update($id, $name, $citizen_identity_card, $phone, $mail, $address, $age, $money, $id_card, $id_branch);
            if ($result) {
                $this->jsonResponse(false, "", "Success!");
                die();
            } else {
                $this->jsonResponse(true, "Something wrong in server!", "Failed!");
                die();
            }
        } else {
            $this->jsonResponse(true, "No data found ! request body must has format {name:'',mail:''}", "Failed!");
        }
    }

    public function AddCustomer($customer)
    {
        $isValid = true;
        if (count($customer) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {name:'',mail:'',phone:''}", "result" => "Failed!"));
        }
        if (trim($customer["name"]) === "" || trim($customer["mail"]) === "" || trim($customer["password"]) === "" || trim($customer["citizen_identity_card"]) === "" || trim($customer["phone"]) === "" || trim($customer["address"]) === "" || trim($customer["age"]) === "" || trim($customer["money"]) === "" || trim($customer["id_card"]) === "" || trim($customer["id_branch"]) === "") {
            $isValid = false;
            if (trim($customer["name"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer name found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["mail"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer mail found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["citizen_identity_card"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer citizen_identity_card found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["phone"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer phone found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["address"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer address found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["age"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer age found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["money"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer money found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["id_card"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer id_card found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["password"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer password found in body data!", "result" => "Failed!"));
                die();
            }
            if (trim($customer["id_branch"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No customer id_branch found in body data!", "result" => "Failed!"));
                die();
            }
            echo json_encode(array("query_err" => true, "err_detail" => "No customer found in body data!", "result" => "Failed!"));
        }
        if ($isValid) {
            $model = $this->model("CustomerModel");
            $result = $model->Add($customer["name"], $customer["password"], $customer["citizen_identity_card"], $customer["phone"], $customer["mail"], $customer["address"], $customer["age"], $customer["money"], $customer["id_card"], $customer["id_branch"]);
            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
    }

    public function changePassword($oldPassword, $newPassword, $id)
    {
        $isValid = true;
        if (trim($oldPassword) === "" || trim($newPassword) === "") {
            $isValid = false;
            if (trim($oldPassword) === "") {
                $this->jsonResponse(true, "Old password is null!", "Failed!");
                die();
            }
            if (trim($newPassword) === "") {
                $this->jsonResponse(true, "New password is null!", "Failed!");
                die();
            }
        }
        if ($oldPassword === $newPassword) {
            $this->jsonResponse(true, "Old password equal new password!", "Failed!");
            die();
        }
        if ($isValid) {
            $this->connectModel("CustomerModel");
            if ($this->model_->checkPassword($oldPassword, $id)) {
                if ($this->model_->changePassword($newPassword, $id)) {
                    $this->jsonResponse(false, "", "Success!");
                    die();
                }
            }
            $this->jsonResponse(true, "Old password incorrect!", "Failed!");
        }
    }

    public function Balance($id)
    {
        $this->connectModel("CustomerModel");

        if ($this->model_->getCustomerById($id) !== null) {
            $result = $this->model_->getBalanceById($id);
            if ($result > 0) {
                $this->jsonResponse(false, "", ["balance" => $result]);
            }
            die();
        }
        $this->jsonResponse(true, "No customer found by id : $id", "Failed!");
    }
}