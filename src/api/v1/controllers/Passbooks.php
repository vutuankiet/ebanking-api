<?php

class Passbooks extends Controller
{
    public function Index($id = null): void
    {
        $model = $this->model("PassbookModel");

        if ($id === null) {
            //        get all passbook function
            $passbooks = $model->getAllPassbook();
            if ($passbooks !== null) {
                $this->jsonResponse(false, "", $passbooks);
            } else {
                $this->jsonResponse(true, "no passbook in server!", []);
            }
        } else {
//            get by id
            $passbooks = $model->getPassbookById($id);
            if ($passbooks !== null) {
                $this->jsonResponse(false, "", $passbooks);
            } else {
                $this->jsonResponse(true, "Not Found!", []);
            }
        }
        die();
    }

    public function GetPassbookByCustomer($token)
    {
        $model = $this->model("PassbookModel");
        $result = $model->getPassbookByCustomer($token);
        if ($result !== null) {
            echo json_encode(array("Customer" => $token, "query_err" => "false", "err_detail" => "", "results" => $result));

        } else {
            echo json_encode(array("Customer" => $token, "query_err" => "false", "err_detail" => "", "results" => [], "message" => "No passbook found by token : $token!"));
        }
        die();

    }

    public function RemoveById($id): void
    {
        if ($id === "") {
            echo json_encode(array("query_err" => true, "err_detail" => "Id incorrect!", "results" => []));
        }
        $this->connectModel("PassbookModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
        die();
    }

    public function UpdateById($passbook)
    {
        if (is_array($passbook) && count($passbook) > 0) {
            $this->connectModel("PassbookModel");
            $id_customer = $passbook["id_customer"] ?? "";
            $money = $passbook["money"] ?? "";
            $id_category_passbook = $passbook["id_category_passbook"] ?? "";
            $id = $passbook["id_passbook"] ?? "";
            $result = $this->model_->Update($id, $id_customer, $money, $id_category_passbook);
            if ($result) {
                $this->jsonResponse(false, "", "Success!");
            } else {
                $this->jsonResponse(true, "Something wrong in server!", "Failed!");
            }
        } else {
            $this->jsonResponse(true, "No data found ! request body must has format {id_customer:''}", "Failed!");
        }
        die();
    }

    public function AddPassbook($passbook)
    {
        $isValid = true;
        if (count($passbook) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {id_customer}", "result" => "Failed!"));
        }
        if (trim($passbook["id_customer"]) === "" || trim($passbook["money"]) === "" || trim($passbook["id_category_passbook"]) === "") {
            $isValid = false;
            if (trim($passbook["id_customer"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No passbook id_customer found in body data!", "result" => "Failed!"));
            }
            if (trim($passbook["money"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No passbook money found in body data!", "result" => "Failed!"));
            }
            if (trim($passbook["id_category_passbook"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No passbook id_category_passbook found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No passbook found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("PassbookModel");

            $result = $model->Add($passbook["id_customer"], $passbook["money"], $passbook["id_category_passbook"]);

            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
        die();
    }
}