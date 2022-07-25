<?php

class BranchModel extends DB
{
    public function getAllBranch()
    {
        try {
            $sql = "SELECT * FROM branch";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
            return null;
        } catch (SQLiteException $ex) {
            trigger_error($ex->getMessage());
            die();
        }
    }

    public function getBranchById($id)
    {
        try {
            $sql = "SELECT * FROM branch WHERE id_branch = $id;";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
            return null;
        } catch (SQLiteException $ex) {
            trigger_error($ex->getMessage());
            die();
        }
    }

    public function Add($location, $name)
    {
        try {
            $sql = "SELECT * FROM branch WHERE location_branch LIKE '" . "%" . $location . "%'";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
            return null;
        } catch (SQLiteException $ex) {
            trigger_error($ex->getMessage());
            die();
        }
    }

    public function getBranchByLocation($location)
    {
        try {
            $sql = "SELECT * FROM branch WHERE location_branch LIKE '" . "%" . $location . "%'";
            $result = $this->executeSelect($sql);
            if (is_array($result) && count($result) > 0) {
                return $result;
            }
            return null;
        } catch (SQLiteException $ex) {
            trigger_error($ex->getMessage());
            die();
        }
    }
}