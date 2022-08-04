<?php

class HistoryTransactions extends Controller
{
    public function Index($id = null): void
    {
        $model = $this->model("HistoryTransactionModel");

        if ($id === null) {
            //        get all HistoryTransaction function
            $transactions = $model->getAllHistoryTransaction();
            if ($transactions !== null) {
                $this->jsonResponse(false, "", $transactions);
            } else {
                $this->jsonResponse(true, "no history transaction in server!", []);
            }
        } else {
//            get by id
            $transactions = $model->getHistoryTransactionById($id);
            if ($transactions !== null) {
                $this->jsonResponse(false, "", $transactions);
            } else {
                $this->jsonResponse(true, "Not Found!", []);
            }
        }
        die();
    }

    public function GetHistoryTransactionByCustomer($id_customer)
    {
        $model = $this->model("HistoryTransactionModel");
        $result = $model->getHistoryTransactionByCustomer($id_customer);
        if ($result !== null) {
            echo json_encode(array("Customer" => $id_customer, "query_err" => "false", "err_detail" => "", "results" => $result));

        } else {
            echo json_encode(array("Customer" => $id_customer, "query_err" => "false", "err_detail" => "", "results" => [], "message" => "No location found by keyword!"));
        }
        die();

    }

    public function RemoveById($id): void
    {
        if ($id === "") {
            echo json_encode(array("query_err" => true, "err_detail" => "Id incorrect!", "results" => []));
        }
        $this->connectModel("HistoryTransactionModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
        die();
    }

    public function UpdateById($transactions)
    {
        if (is_array($transactions) && count($transactions) > 0) {
            $this->connectModel("HistoryTransactionModel");
            $id_category_transaction = $transactions["id_category_transaction"] ?? "";
            $money = $transactions["money"] ?? "";
            $id_customer = $transactions["id_customer"] ?? "";
            $part_transaction = $transactions["part_transaction"] ?? "";
            $status = $transactions["status"] ?? "";
            $id = $transactions["id_transaction"] ?? "";
            $result = $this->model_->Update($id, $id_category_transaction, $money, $id_customer, $part_transaction, $status);
            if ($result) {
                $this->jsonResponse(false, "", "Success!");
            } else {
                $this->jsonResponse(true, "Something wrong in server!", "Failed!");
            }
        } else {
            $this->jsonResponse(true, "No data found ! request body must has format {id_category_transaction:'',money:'',id_customer:''}", "Failed!");
        }
        die();
    }

    public function AddHistoryTransaction($transactions)
    {
        $isValid = true;
        if (count($transactions) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {id_category_transaction:'',money:'',id_customer:''}", "result" => "Failed!"));
        }
        if (trim($transactions["id_category_transaction"]) === "" || trim($transactions["money"]) === "" || trim($transactions["id_customer"]) === "" || trim($transactions["part_transaction"]) === "" || trim($transactions["status"]) === "") {
            $isValid = false;
            if (trim($transactions["id_category_transaction"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction id_category_transaction found in body data!", "result" => "Failed!"));
            }
            if (trim($transactions["money"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction money found in body data!", "result" => "Failed!"));
            }
            if (trim($transactions["id_customer"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction id_customer found in body data!", "result" => "Failed!"));
            }
            if (trim($transactions["part_transaction"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction part_transaction found in body data!", "result" => "Failed!"));
            }
            if (trim($transactions["status"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction status found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("HistoryTransactionModel");

            $result = $model->Add($transactions["id_category_transaction"], $transactions["money"], $transactions["id_customer"], $transactions["part_transaction"], $transactions["status"]);

            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
        die();
    }
}