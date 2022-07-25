<?php


class App
{

    protected $controller = "Home";
    protected string $action = "Index";
    protected array $params = [];
    protected string $api_uri = "api";
    protected string $api_version = "v1";
    protected string $api_method = "GET";

    function __construct()
    {
        $config = parse_ini_file('./src/Config.init');
        $this->api_version = $config['version'];
        $arr = $this->UrlProcess();
        switch ($this->api_method) {
            case "GET":
                $this->GetProcess($arr);
                break;
            case "POST":
                $this->PostProcess($arr);
                break;
            case "PUT":
                $this->PutProcess($arr);
                break;
            case "DELETE":
                $this->DeletedProcess($arr);
                break;
            default:
                break;
        }
    }

    function PostProcess($arr): void
    {
        $this->setController($arr);
        $body = json_decode(file_get_contents("php://input"));

//        branch

        if (isset($body->name_branch) && isset($body->location_branch)) {
//            create branch
            call_user_func_array([$this->controller, "AddBranch"], [["location" => $body->location_branch, "name" => $body->name_branch]]);
        } else if (isset($body->location)) {
            // get by location
            call_user_func_array([$this->controller, "GetBranchByLocation"], [$body->location]);
        } else {
            echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "not found!"));
        }
//        others


    }

    function PutProcess($arr): void
    {

        $body = json_decode(file_get_contents("php://input"));
        $this->setController($arr);
        $this->params = $arr ? array_values($arr) : [];
//        update branch
        if (!count($this->params) <= 0) {
            if (isset($body->name_branch) || isset($body->location_branch)) {
                $name = $body->name_branch ?? "";
                $location = $body->location_branch ?? "";
                call_user_func_array([$this->controller, "UpdateById"], [array("name" => $name, "location" => $location, "id" => $this->params[0])]);
            }
        } else {
            echo json_encode(array("query_err" => true, "err_detail" => "No id in url!", "Id not found!"));
        }

    }

    function DeletedProcess($arr): void
    {
        $this->setController($arr);
        $this->params = $arr ? array_values($arr) : [];
        call_user_func_array([$this->controller, "RemoveById"], $this->params);
    }

//    function unset_arr($arr): void
//    {
//        unset($arr[0]);
//        unset($arr[1]);
//        unset($arr[2]);
//    }

    function setController(&$arr): void
    {
        if (isset($arr[0])) {
            $config = parse_ini_file("./src/Config.init");
            $this->api_version = $config["version"];
            if ($arr[0] == $this->api_uri && $arr[1] == $this->api_version) {
                // Controller
                if (file_exists("./src/$this->api_uri/$this->api_version/controllers/" . ucfirst($arr[2]) . ".php")) {
                    $this->controller = ucfirst($arr[2]);
                    unset($arr[2]);
                    unset($arr[1]);
                    unset($arr[0]);
                }
            }
        }
        $controller_path = "./src/$this->api_uri/$this->api_version/controllers/" . $this->controller . ".php";
        if (file_exists($controller_path)) {
            include $controller_path;
        } else {
            echo "path $controller_path not exit!";
            die();
        }
        if (class_exists($this->controller)) {
            $this->controller = new $this->controller;
        } else {
            echo "Controller $this->controller not exit!";
            die();
        }
    }

    function GetProcess($arr): void
    {
        $this->setController($arr);
        // Action
        if (isset($arr[3])) {
            if (method_exists($this->controller, ucfirst($arr[3]))) {
                $this->action = ucfirst($arr[3]);
                unset($arr[3]);
            }
        }
        // Params
        $this->params = $arr ? array_values($arr) : [];
        call_user_func_array([$this->controller, $this->action], $this->params);
    }

    function UrlProcess()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->api_method = 'GET';
//                api/v1/branches
//                ['api','v1','branches']
                return explode("/", filter_var(trim($_GET["url"], "/")));
                break;
            case 'PUT':
                $this->api_method = 'PUT';
                return explode("/", filter_var(trim($_GET["url"], "/")));
                break;
            case 'POST':
                $this->api_method = 'POST';
                return explode("/", filter_var(trim($_GET["url"], "/")));
                break;
            case 'DELETE';
                $this->api_method = 'DELETE';
                return explode("/", filter_var(trim($_GET["url"], "/")));
                break;
            default:
                break;
        }
    }
}

?>