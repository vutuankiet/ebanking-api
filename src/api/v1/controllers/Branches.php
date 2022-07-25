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
                echo json_encode($branches);
            } else {
                echo json_encode([]);
            }
        } else {
//            get by id
            $branches = $model->getBranchById($id);
            if ($branches !== null) {
                echo json_encode($branches);
            } else {
                echo json_encode([]);
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

    function AddBranch($branch)
    {
        if (count($branch) <= 0) {
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {name:'',location:''}", "result" => "Failed!"));
        }

        $model = $this->model("BranchModel");

        $result = $model->Add($branch["location"], $branch["name"]);

        if ($result) {
            echo json_encode(array("query_err" => false, "err_detail" => false, "result" => "Success!"));
        } else {
            echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
        }
    }
}