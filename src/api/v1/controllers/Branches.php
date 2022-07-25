<?php

class Branches extends Controller
{
    public function Index($id = null)
    {
        $model = $this->model("BranchModel");

        if ($id === null) {
            //        get all branch function
            $branches = $model->getAllBranch();
            if ($branches !== null) {
                $this->jsonResponse(false, "", $branches);
            } else {
                $this->jsonResponse(true, "no branch in server!", []);
            }
        } else {
//            get by id
            $branches = $model->getBranchById($id);
            if ($branches !== null) {
                $this->jsonResponse(false, "", $branches);
            } else {
                $this->jsonResponse(true, "Not Found!", []);
            }
        }
    }

    public function GetBranchByLocation($location)
    {
        $model = $this->model("BranchModel");
        $result = $model->getBranchByLocation($location);
        if ($result !== null) {
            echo json_encode(array("location" => $location, "query_err" => "false", "err_detail" => "", "results" => $result));
        } else {
            echo json_encode(array("location" => $location, "query_err" => "false", "err_detail" => "", "results" => [], "message" => "No location found by keyword!"));
        }
    }

    public function RemoveById($id): void
    {
        if ($id === "") {
            echo json_encode(array("query_err" => true, "err_detail" => "Id incorrect!", "results" => []));
            die();
        }
        $this->connectModel("BranchModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
    }

    public function AddBranch($branch)
    {
        $isValid = true;
        if (count($branch) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {name:'',location:''}", "result" => "Failed!"));
        }
        if (trim($branch["name"]) === "" || trim($branch["location"]) == "") {
            $isValid = false;
            if (trim($branch["name"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No branch name found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No branch location found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("BranchModel");

            $result = $model->Add($branch["location"], $branch["name"]);

            if ($result) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "Success!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
    }
}