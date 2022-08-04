<?php

class CategoryTransactions extends Controller
{
    public function Index($id = null)
    {
        $model = $this->model("CategoryTransactionModel");

        if ($id === null) {
            //        get all CategoryTransaction function
            $cateTransaction = $model->getAllCateTransaction();
            if ($cateTransaction !== null) {
                $this->jsonResponse(false, "", $cateTransaction);
            } else {
                $this->jsonResponse(true, "no Category Transaction in server!", []);
            }
        } else {
//            get by id
            $cateTransaction = $model->getCateTransactionById($id);
            if ($cateTransaction !== null) {
                $this->jsonResponse(false, "", $cateTransaction);
            } else {
                $this->jsonResponse(true, "Not Found!", []);
            }
        }
        die();
    }

    public function RemoveById($id): void
    {
        if ($id === "") {
            echo json_encode(array("query_err" => true, "err_detail" => "Id incorrect!", "results" => []));
        }
        $this->connectModel("CategoryTransactionModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
        die();
    }

    public function UpdateById($cateTransaction)
    {
        if (is_array($cateTransaction) && count($cateTransaction) > 0) {
            $this->connectModel("CategoryTransactionModel");
            $name_transaction = $cateTransaction["name_transaction"] ?? "";
            $description = $cateTransaction["description"] ?? "";
            $fee_transaction = $cateTransaction["fee_transaction"] ?? "";
            $id = $cateTransaction["id_category_transaction"] ?? "";
            $result = $this->model_->Update($id, $name_transaction, $description, $fee_transaction);
            if ($result) {
                $this->jsonResponse(false, "", "Success!");
            } else {
                $this->jsonResponse(true, "Something wrong in server!", "Failed!");
            }
        } else {
            $this->jsonResponse(true, "No data found ! request body must has format {name_transaction:'',description:'',fee_transaction:''}", "Failed!");
        }
        die();
    }

    public function AddCategoryTransaction($cateTransaction)
    {
        $isValid = true;
        if (count($cateTransaction) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {name_transaction:'',description:'',fee_transaction:''}", "result" => "Failed!"));
        }
        if (trim($cateTransaction["name_transaction"]) === "" || trim($cateTransaction["description"]) === "" || trim($cateTransaction["fee_transaction"]) === "") {
            $isValid = false;
            if (trim($cateTransaction["name_transaction"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category transaction name_transaction passbook found in body data!", "result" => "Failed!"));
            }
            if (trim($cateTransaction["description"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category transaction description found in body data!", "result" => "Failed!"));
            }
            if (trim($cateTransaction["fee_transaction"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category transaction fee_transaction found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No category transaction found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("CategoryTransactionModel");

            $result = $model->Add($cateTransaction["name_transaction"], $cateTransaction["description"], $cateTransaction["fee_transaction"]);

            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
        die();
    }
}