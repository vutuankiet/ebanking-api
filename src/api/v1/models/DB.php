<?php

class DB
{
    public $con = null;
    protected $servername = "localhost";
    protected $username = "root";
    protected $password = "";
    protected $dbname = "ebanking";

    function __construct()
    {
        $this->con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname, 3306) or die("Connected failed!");
        mysqli_query($this->con, "SET NAMES 'utf8'");
    }

    public function Open()
    {
        $this->con = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname, 3306);
        mysqli_query($this->con, "SET NAMES 'utf8'");
    }

    public function Close()
    {
        mysqli_close($this->con);
    }

    public function jsonResponse($query_err, $err_detail, $result): void
    {
        echo json_encode(array("query_err" => $query_err, "err_detail" => $err_detail, "result" => $result));
    }

    public function trigger_response(Exception $ex): void
    {
        $this->jsonResponse(true, $ex->getMessage(), "Failed!");
    }

    public
    function executeSelect($sql)
    {
        try {
            if ($this->con) {
                $result = $this->con->query($sql);
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data [] = $row;
                }
                return $data;
            } else {
                trigger_error("No DB connected!");
            }
        } catch (SQLiteException $e) {
            trigger_error($e->getMessage());
            die();
        }

    }

    public
    function executeUpdateAndInsert($sql)
    {
        try {
            if ($this->con) {
                $result = $this->con->query($sql);
                return $result === true;
            } else {
                trigger_error("No DB connected!");
            }
        } catch (SQLiteException $e) {
            trigger_error($e->getMessage());
            die();
        }
    }

//    public
//    function GetAllFieldOfTable($table_name)
//    {
//        try {
//            $sql = "SELECT GROUP_CONCAT(COLUMN_NAME) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='$this->dbname' AND TABLE_NAME='$table_name'";
//            $result = $this->con->query($sql);
//            return $result->fetch_assoc();
//        } catch (SQLiteException $e) {
//            trigger_error($e->getMessage());
//            die();
//        }
//    }

//    public function CheckFieldHasExit($fields, $table_name)
//    {
//        if (trim($table_name) !== '' && is_array($fields) && count($fields) > 0) {
//            $query_result = $this->GetAllFieldOfTable($table_name);
//            $fields_string = '';
//            foreach ($query_result as $item) {
//                $fields_string = $item;
//            }
//            $fields = [];
//            if (is_string($fields_string) && trim($fields_string) !== '') {
//                $fields = explode(",", $fields_string);
//            }
//            foreach ($fields as $field) {
//
//            }
//        } else {
//            trigger_error("Inline 34 : DB.php err : table name null or array field null");
//            return false;
//        }
//    }
//
//    public function Insert($data, $table)
//    {
//        if (trim($table) !== "" && is_string($table)) {
//            $insert_sql = "INSERT INTO  ";
//
//        } else {
//            trigger_error("Table name incorrect!");
//        }
//    }

//    public function Execute($sql)
//    {
//        try {
//
//        } catch (SQLiteException $e) {
//            echo $e->getMessage();
//            die();
//        }
//    }
}

?>