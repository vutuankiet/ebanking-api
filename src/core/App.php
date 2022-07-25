<?php


class App
{

    protected $controller = "Home";
    protected $action = "Index";
    protected $params = [];
    protected $api_uri = "api";
    protected $api_version = "v1";
    protected $api_method = "GET";

    function __construct()
    {
        $config = parse_ini_file('./src/Config.init');
        $this->api_version = $config['version'];
        $arr = $this->UrlProcess();
        switch ($this->api_method) {
            case "GET":
                $this->GetProccess($arr);
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

    function PostProcess($arr)
    {
        $this->setController($arr);
        $body = json_decode(file_get_contents("php://input"));
        if (isset($body->name_branch) && isset($body->location_brach)) {
//            create branch
            call_user_func_array([$this->controller, "AddBranch"], ["location" => $body->location_brach, "name" => $body->name_branch]);
        } else if (isset($body->location)) {
            // get by location
            call_user_func_array([$this->controller, "GetBranchByLocation"], [$body->location]);
        }
    }

    function PutProcess($arr)
    {

    }

    function DeletedProcess($arr)
    {

    }

    function setController($arr)
    {
        if (isset($arr[0])) {
            if ($arr[0] == $this->api_uri && $arr[1] == $this->api_version) {
                // Controller
                if (file_exists("./src/$this->api_uri/$this->api_version/controllers/" . ucfirst($arr[2]) . ".php")) {
                    $this->controller = ucfirst($arr[2]);
                    unset($arr[2]);
                    unset($arr[0]);
                    unset($arr[1]);
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
                $data = [];
                break;
            default:
                break;
        }
    }

}

?>