<?php

class CategoryPassbooks extends Controller
{
    public function Index($id = null)
    {
        $model = $this->model("CategoryPassbookModel");

        if ($id === null) {
            //        get all CategoryPassbook function
            $catePassbook = $model->getAllCategoryPassbook();
            if ($catePassbook !== null) {
                $this->jsonResponse(false, "", $catePassbook);
            } else {
                $this->jsonResponse(true, "no Category Passbook in server!", []);
            }
        } else {
//            get by id
            $catePassbook = $model->getCategoryPassbookById($id);
            if ($catePassbook !== null) {
                $this->jsonResponse(false, "", $catePassbook);
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
        $this->connectModel("CategoryPassbookModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
        die();
    }

    public function UpdateById($catePassbook)
    {
        if (is_array($catePassbook) && count($catePassbook) > 0) {
            $this->connectModel("CategoryPassbookModel");
            $name_passbook = $catePassbook["name_passbook"] ?? "";
            $period = $catePassbook["period"] ?? "";
            $interest_rate = $catePassbook["interest_rate"] ?? "";
            $description = $catePassbook["description"] ?? "";
            $id = $catePassbook["id_category_passbook"] ?? "";
            $result = $this->model_->Update($id, $name_passbook, $period, $interest_rate, $description);
            if ($result) {
                $this->jsonResponse(false, "", "Success!");
            } else {
                $this->jsonResponse(true, "Something wrong in server!", "Failed!");
            }
        } else {
            $this->jsonResponse(true, "No data found ! request body must has format {name_passbook:'',period:'',interest_rate:''}", "Failed!");
        }
        die();
    }

    public function AddCategoryPassbook($catePassbook)
    {
        $isValid = true;
        if (count($catePassbook) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {id_customer}", "result" => "Failed!"));
        }
        if (trim($catePassbook["name_passbook"]) === "" || trim($catePassbook["period"]) === "" || trim($catePassbook["interest_rate"]) === "" || trim($catePassbook["description"]) === "") {
            $isValid = false;
            if (trim($catePassbook["name_passbook"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category passbook name passbook found in body data!", "result" => "Failed!"));
            }
            if (trim($catePassbook["period"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category passbook period found in body data!", "result" => "Failed!"));
            }
            if (trim($catePassbook["interest_rate"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category passbook interest_rate found in body data!", "result" => "Failed!"));
            }
            if (trim($catePassbook["description"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No category passbook description found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No category passbook found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("CategoryPassbookModel");

            $result = $model->Add($catePassbook["name_passbook"], $catePassbook["period"], $catePassbook["interest_rate"], $catePassbook["description"]);

            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
        die();
    }
}