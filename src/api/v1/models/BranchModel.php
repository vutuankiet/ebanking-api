<?php

class BranchModel extends DB
{

    public function getAllBranch()
    {
        try {
            $sql = "SELECT * FROM branch WHERE state = 1;";
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

    public function getBranchById($id)
    {
        try {
            $sql = "SELECT * FROM branch WHERE id_branch = $id AND state = 1;";
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

    public function CheckBranchHasExit($name, $location)
    {
        try {
            $sql = "SELECT * FROM branch WHERE name_branch = '$name' AND location_branch ='$location';";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {

                return true;
            }
            return false;
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Add($location, $name): bool
    {
        try {

            if (!$this->CheckBranchHasExit($name, $location)) {
                $sql = "INSERT INTO branch(name_branch,location_branch,updated_at) VALUE('$name','$location',null);";
                return $this->executeUpdateAndInsert($sql);
            } else {

                $sql = "SELECT * FROM branch WHERE name_branch = '$name' AND location_branch = '$location' AND state = 0";
                $result = $this->executeSelect($sql);
                if (is_array($result) && count($result) > 0) {
                    $id = $result[0]["id_branch"];
                    $sql_update = "UPDATE branch SET state = 1 WHERE id_branch = $id";
                    return $this->executeUpdateAndInsert($sql_update);
                }
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Branch name and location has exit!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }


    public function Update($id, $name, $location)
    {
        try {
            $checkExit = $this->getBranchById($id);
            if ($checkExit !== null) {
                $checkName = $name === "" ? "name_branch" : "'$name'";
                $checkLocation = $location === "" ? "location_branch" : "'$location'";
                $sql = "UPDATE branch SET name_branch = $checkName, location_branch = $checkLocation WHERE id_branch = $id;";
                return $this->executeUpdateAndInsert($sql);
            } else {
                $this->jsonResponse(true, "Branch not found by id : $id", "Failed!");
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
            if ($this->getBranchById($id) !== null) {
                $sql = "UPDATE branch SET state = 0 WHERE id_branch = $id;";
                return $this->executeUpdateAndInsert($sql);
            }
            $this->jsonResponse(true, "No branch found by id : $id", "Failed!");
            die();
        } catch (Exception $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function getBranchByLocation($location)
    {
        try {
            $sql = "SELECT * FROM branch WHERE location_branch LIKE '" . "%" . $location . "%' AND state = 1;";
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
}