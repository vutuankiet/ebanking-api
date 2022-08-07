<?php

class Transactions extends Controller
{

    public function AddTransaction($transactions)

    {
        $isValid = true;
        if (count($transactions) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {id_category_transaction:'',money:'',from:''}", "result" => "Failed!"));
        }
        if (trim($transactions["money"]) === "" || trim($transactions["from"]) === "") {
            $isValid = false;
            if (trim($transactions["money"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction money found in body data!", "result" => "Failed!"));
            }
            if (trim($transactions["from"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction 'from' found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No history transaction found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("TransactionModel");

            $result = $model->AddTransaction($transactions["id_category_transaction"],$transactions["money"], $transactions["from"]);

            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
        die();
    }
}