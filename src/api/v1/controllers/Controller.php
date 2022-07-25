<?php

class Controller
{
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