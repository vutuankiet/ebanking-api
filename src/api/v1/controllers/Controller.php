<?php

class Controller
{
    public $model_ = null;

    public function connectModel($model_name): void
    {
        if ($model_name !== "") {
            $this->model_ = $this->model($model_name);
        }
    }

    public function jsonResponse($query_err, $err_detail, $result): void
    {
        echo json_encode(array("query_err" => $query_err, "err_detail" => $err_detail, "result" => $result));
    }

    public function model($model)
    {
        $config = parse_ini_file('./src/Config.init');
        $version = $config['version'];
        if (file_exists("./src/api/$version/models/" . $model . ".php")) {
            require_once "./src/api/$version/models/" . $model . ".php";
            return new $model;
        }
        trigger_error("Model $model not found!");
    }

}

?>