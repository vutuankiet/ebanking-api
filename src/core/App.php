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
            if (get_class($this->controller) === "Branches") {
                $location = $body->location_branch ?? "";
                $name = $body->name_branch ?? "";
                call_user_func_array([$this->controller, "AddBranch"], [["location" => $location, "name" => $name]]);
            }
//            create branch
        } else if (isset($body->location)) {
            if (get_class($this->controller) === "Branches") {
                call_user_func_array([$this->controller, "GetBranchByLocation"], [$body->location]);
            }
        } else if (isset($body->name) && isset($body->citizen_identity_card) && isset($body->phone) && isset($body->mail) && isset($body->address) && isset($body->age) && isset($body->money) && isset($body->id_card) && isset($body->id_branch) && isset($body->password)) {
//            create customer
            if (get_class($this->controller) === "Customers") {
                if (isset($arr[3]) && $arr[3] === "signup") {
                    call_user_func_array([$this->controller, "AddCustomer"], [["id_branch" => $body->id_branch, "name" => $body->name, "citizen_identity_card" => $body->citizen_identity_card, "password" => $body->password, "phone" => $body->phone, "mail" => $body->mail, "address" => $body->address, "age" => $body->age, "money" => $body->money, "id_card" => $body->id_card]]);
                }
            }
        } else if (isset($body->id_branch)) {
            // get by branch
            if (get_class($this->controller) === "Branches") {
                if (!isset($arr[3])) {
                    call_user_func_array([$this->controller, "GetCustomerByBranch"], [$body->id_branch]);
                }
            }
        } else if (isset($body->phone) && isset($body->password)) {
            if (get_class($this->controller) === "Customers") {
                $phone = $body->phone ?? "";
                $password = $body->password ?? "";
                if (isset($arr[3]) && $arr[3] == "signin") {
                    call_user_func_array([$this->controller, "SignIn"], [["phone" => $phone, "password" => $password]]);
                }
            }
            //      sign in
        }else if (isset($body->id_customer) && isset($body->money) && isset($body->period) && isset($body->interest_rate) && isset($body->status) && isset($body->description)) {
            if (get_class($this->controller) === "Passbooks") {
                $id_customer = $body->id_customer ?? "";
                $money = $body->money ?? "";
                $period = $body->period ?? "";
                $interest_rate = $body->interest_rate ?? "";
                $status = $body->status ?? "";
                $description = $body->description ?? "";
                call_user_func_array([$this->controller, "AddPassbook"], [["id_customer" => $id_customer, "money" => $money, "period" => $period, "interest_rate" => $interest_rate, "status" => $status, "description" => $description]]);
            }
    //            create passbook
        } else if (isset($body->id_customer)) {
            if (get_class($this->controller) === "Passbooks") {
                call_user_func_array([$this->controller, "GetPassbookByCustomer"], [$body->id_customer]);
            }
        } else {
            echo json_encode(array("query_err" => false, "err_detail" => "", "result" => "not found!"));
            die();
        }
    }

    function PutProcess($arr): void
    {
        $body = json_decode(file_get_contents("php://input"));
        $this->setController($arr);
        $this->params = $arr ? array_values($arr) : [];
//        update branch

        if (!count($this->params) <= 0) {
            if (isset($body->name_branch) || isset($body->location_branch)) {
                if (get_class($this->controller) === "Branches") {
                    $name = $body->name_branch ?? "";
                    $location = $body->location_branch ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("name" => $name, "location" => $location, "id" => $this->params[0])]);
                }
            } else if (isset($body->id_customer) || isset($body->money) || isset($body->period) || isset($body->interest_rate) || isset($body->status) || isset($body->description)) {
                if (get_class($this->controller) === "Passbooks") {
                    $id_customer = $body->id_customer ?? "";
                    $money = $body->money ?? "";
                    $period = $body->period ?? "";
                    $interest_rate = $body->interest_rate ?? "";
                    $status = $body->status ?? "";
                    $description = $body->description ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("id_customer" => $id_customer, "money" => $money, "period" => $period, "interest_rate" => $interest_rate, "status" => $status, "description" => $description, "id_passbook" => $this->params[0])]);
                }
            } else if (isset($body->oldPassword) && isset($body->newPassword)) {
                if (get_class($this->controller) === "Customers") {
                    $newPass = $body->newPassword ?? "";
                    $oldPass = $body->oldPassword ?? "";
                    call_user_func_array([$this->controller, "changePassword"], [$oldPass, $newPass, $this->params[0]]);
                }
            } else if (isset($body->name) || isset($body->citizen_identity_card) || isset($body->phone) || isset($body->mail) || isset($body->address) || isset($body->age) || isset($body->money) || isset($body->id_card) || isset($body->id_branch)) {
                if (get_class($this->controller) === "Customers") {
                    $name = $body->name ?? "";
                    $id_branch = $body->id_branch ?? "";
                    $citizen_identity_card = $body->citizen_identity_card ?? "";
                    $phone = $body->phone ?? "";
                    $mail = $body->mail ?? "";
                    $address = $body->address ?? "";
                    $age = $body->age ?? "";
                    $money = $body->money ?? "";
                    $id_card = $body->id_card ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("mail" => $mail, "address" => $address, "name" => $name, "id_branch" => $id_branch, "citizen_identity_card" => $citizen_identity_card, "age" => $age, "phone" => $phone, "money" => $money, "id_card" => $id_card, "id" => $this->params[0])]);
                }
            } else {
                echo json_encode(array("query_err" => true, "err_detail" => "Not found!", "result" => "Failed!"));
            }
        } else {
            echo json_encode(array("query_err" => true, "err_detail" => "No data in body!", "Id not found!"));
            die();
        }
    }

    function DeletedProcess($arr): void
    {
        $this->setController($arr);
        $this->params = $arr ? array_values($arr) : [];
        call_user_func_array([$this->controller, "RemoveById"], $this->params);
    }

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