<?php

class Controller
{
    public $model_ = null;

    public function getAuthorizationHeader(): ?string
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    public function getBearerToken()
    {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

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