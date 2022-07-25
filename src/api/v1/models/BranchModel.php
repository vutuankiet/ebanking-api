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
                $sql = "INSERT INTO branch(name_branch,location_branch) VALUE('$name','$location');";
                return $this->executeUpdateAndInsert($sql);
            }
            echo json_encode(array("query_err" => true, "err_detail" => "Branch name and location has exit!", "result" => []));
            die();
        } catch (SQLiteException $ex) {
            $this->trigger_response($ex);
            die();
        }
    }

    public function HasExitToRemove($id)
    {
        try {

        } catch (SQLiteException $ex) {

        }
    }

    public function Remove($id)
    {
        try {
            $sql = "UPDATE branch SET state = 0 WHERE id_branch = $id;";
            return $this->executeUpdateAndInsert($sql);
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