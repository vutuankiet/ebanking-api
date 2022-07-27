<?php

class Cards extends Controller
{
    public function Index($id = null)
    {
        $model = $this->model("CardModel");

        if ($id === null) {
            //        get all card function
            $cards = $model->getAllCard();
            if ($cards !== null) {
                $this->jsonResponse(false, "", $cards);
            } else {
                $this->jsonResponse(true, "no card in server!", []);
            }
        } else {
//            get by id
            $cards = $model->getCardById($id);
            if ($cards !== null) {
                $this->jsonResponse(false, "", $cards);
            } else {
                $this->jsonResponse(true, "Not Found!", []);
            }
        }
        die();
    }

    public function GetCardByCustomer($id_customer)
    {
        $model = $this->model("CardModel");
        $result = $model->getCardByCustomer($id_customer);
        if ($result !== null) {
            echo json_encode(array("customer" => $id_customer, "query_err" => "false", "err_detail" => "", "results" => $result));

        } else {
            echo json_encode(array("customer" => $id_customer, "query_err" => "false", "err_detail" => "", "results" => [], "message" => "No customer found by keyword!"));
        }
        die();

    }

    public function RemoveById($id): void
    {
        if ($id === "") {
            echo json_encode(array("query_err" => true, "err_detail" => "Id incorrect!", "results" => []));
        }
        $this->connectModel("CardModel");
        $result = $this->model_->Remove($id);
        if ($result) {
            $this->jsonResponse(false, "", "Success!");
        } else {
            $this->jsonResponse(true, "Something wrong in server!", "Failed!");
        }
        die();
    }

    public function UpdateById($card)
    {
        if (is_array($card) && count($card) > 0) {
            $this->connectModel("CardModel");
            $pin = $card["pin"] ?? "";
            $status = $card["status"] ?? "";
            $id = $card["id_card"] ?? "";
            $result = $this->model_->Update($id, $pin, $status);
            if ($result) {
                $this->jsonResponse(false, "", "Success!");
            } else {
                $this->jsonResponse(true, "Something wrong in server!", "Failed!");
            }
        } else {
            $this->jsonResponse(true, "No data found ! request body must has format {name_branch:'',location_branch:''}", "Failed!");
        }
        die();
    }

    public function AddCard($card)
    {
        $isValid = true;
        if (count($card) <= 0) {
            $isValid = false;
            echo json_encode(array("query_err" => true, "err_detail" => "No body found body has format {pin:''}", "result" => "Failed!"));
        }
        if (trim($card["pin"]) === "" || trim($card["status"]) === "") {
            $isValid = false;
            if (trim($card["pin"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No card pin found in body data!", "result" => "Failed!"));
            }
            if (trim($card["status"]) === "") {
                echo json_encode(array("query_err" => true, "err_detail" => "No card status found in body data!", "result" => "Failed!"));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "No card found in body data!", "result" => "Failed!"));
            }
        }
        if ($isValid) {
            $model = $this->model("CardModel");

            $result = $model->Add($card["pin"], $card["status"], $card["id_customer"]);

            if ($result["result"]) {
                echo json_encode(array("query_err" => false, "err_detail" => "", "result" => ["success" => true, "id" => $result["id"]]));
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "some err when add document in server!", "result" => "failed!"));
            }
        }
        die();
    }
}