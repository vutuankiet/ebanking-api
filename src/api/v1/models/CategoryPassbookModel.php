<?php

class CategoryPassbookModel extends DB
{
    public function getAllCategoryPassbook()
    {
        try {
            $sql = "SELECT * FROM category_passbook WHERE state = 1;";
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

    public function getCategoryPassbookById($id)
    {
        try {
            $sql = "SELECT * FROM category_passbook WHERE id_category_passbook = $id AND state = 1;";
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

    public function Add($name_passbook, $period, $interest_rate, $description): bool
    {
        try {
            $sql = "INSERT INTO category_passbook(name_passbook, period, interest_rate, description) VALUE('$name_passbook', $period, $interest_rate, $description);";
            return $this->executeUpdateAndInsert($sql);
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $name_passbook, $period, $interest_rate, $description)
    {
        try {
            $checkExit = $this->getCategoryPassbookById($id);
            if ($checkExit !== null) {
                $checkNamePassbook = $name_passbook === "" ? "name_passbook" : "$name_passbook";
                $checkPeriod = $period === "" ? "period" : "$period";
                $checkInterestRate = $interest_rate === "" ? "interest_rate" : "$interest_rate";
                $checkDescription = $description === "" ? "description" : "'$description'";
                $sql = "UPDATE category_passbook SET name_passbook = '$checkNamePassbook', period = $checkPeriod, interest_rate = $checkInterestRate, description = $checkDescription WHERE id_category_passbook = $id;";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Category Passbook not found by id : $id", "Failed!");
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
            if ($this->getCategoryPassbookById($id) !== null) {
                $sql = "UPDATE category_passbook SET state = 0 WHERE id_category_passbook = $id;";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No Category Passbook found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }
}