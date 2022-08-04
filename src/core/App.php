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
        if (get_class($this->controller) === "Branches") {
            if (isset($body->name_branch) && isset($body->location_branch)) {
                $location = $body->location_branch ?? "";
                $name = $body->name_branch ?? "";
                call_user_func_array([$this->controller, "AddBranch"], [["location" => $location, "name" => $name]]);
//            create branch
            } else if (isset($body->location)) {
                call_user_func_array([$this->controller, "GetBranchByLocation"], [$body->location]);
            } else if (isset($body->id_branch)) {
                // get by branch
                if (!isset($arr[3])) {
                    call_user_func_array([$this->controller, "GetCustomerByBranch"], [$body->id_branch]);
                }
            }
        } else if (get_class($this->controller) === "Customers") {

            if (isset($body->name) && isset($body->citizen_identity_card) && isset($body->phone) &&
                isset($body->mail) && isset($body->address) && isset($body->age) && isset($body->money) &&
                (isset($body->id_card)) && isset($body->id_branch) && isset($body->password)) {

                //create
                if (isset($arr[3]) && $arr[3] === "signup") {
                    call_user_func_array([$this->controller, "AddCustomer"], [["id_branch" => $body->id_branch, "name" => $body->name, "citizen_identity_card" => $body->citizen_identity_card, "password" => $body->password, "phone" => $body->phone, "mail" => $body->mail, "address" => $body->address, "age" => $body->age, "money" => $body->money, "id_card" => $body->id_card]]);
                }

            } else if (isset($body->phone) && isset($body->password)) {
                // login
                $phone = $body->phone ?? "";
                $password = $body->password ?? "";
                if (isset($arr[3]) && $arr[3] == "signin") {
                    call_user_func_array([$this->controller, "SignIn"], [["phone" => $phone, "password" => $password]]);
                }
            } else if (isset($body->phone)) {
                //check phone
                call_user_func_array([$this->controller, "CheckPhone"], [["phone" => $body->phone]]);
            } else if (isset($body->mail)) {
                // check mail
                call_user_func_array([$this->controller, "CheckMail"], [["mail" => $body->mail]]);
            } else if (isset($body->citizen_identity_card)) {
                //check card
                call_user_func_array([$this->controller, "CheckCitizenCard"], [["]card" => $body->citizen_identity_card]]);
            }

        } else if (get_class($this->controller) === "Passbooks") {
            if (isset($body->id_customer) && isset($body->money) && isset($body->id_category_passbook)) {
                $id_customer = $body->id_customer ?? "";
                $money = $body->money ?? "";
                $id_category_passbook = $body->id_category_passbook ?? "";
                call_user_func_array([$this->controller, "AddPassbook"], [["id_customer" => $id_customer, "money" => $money, "id_category_passbook" => $id_category_passbook]]);
                //            create passbook
            } else if (isset($body->id_customer)) {
                call_user_func_array([$this->controller, "GetPassbookByCustomer"], [$body->id_customer]);
            }
        } else if (get_class($this->controller) === "CategoryPassbooks") {
            if (isset($body->name_passbook) && isset($body->period) && isset($body->interest_rate) && isset($body->description)) {
                $name_passbook = $body->name_passbook ?? "";
                $period = $body->period ?? "";
                $interest_rate = $body->interest_rate ?? "";
                $description = $body->description ?? "";
                call_user_func_array([$this->controller, "AddCategoryPassbook"], [["name_passbook" => $name_passbook, "period" => $period, "interest_rate" => $interest_rate, "description" => $description]]);
                //            create Category Passbook
            }
        } else if (get_class($this->controller) === "HistoryTransactions") {
            if (isset($body->id_category_transaction) || isset($body->money) || isset($body->id_customer) || isset($body->part_transaction) || isset($body->status)) {
                $id_category_transaction = $body->id_category_transaction ?? "";
                $money = $body->money ?? "";
                $id_customer = $body->id_customer ?? "";
                $part_transaction = $body->part_transaction ?? "";
                $status = $body->status ?? "";
                call_user_func_array([$this->controller, "AddHistoryTransaction"], [["id_category_transaction" => $id_category_transaction, "money" => $money, "id_customer" => $id_customer, "part_transaction" => $part_transaction, "status" => $status]]);
                //            create history transactions
            } else if (isset($body->id_customer)) {
                call_user_func_array([$this->controller, "GetPassbookByCustomer"], [$body->id_customer]);
            }
        } else if (get_class($this->controller) === "CategoryTransactions") {
            if (isset($body->name_transaction) && isset($body->description) && isset($body->fee_transaction)) {
                $name_transaction = $body->name_transaction ?? "";
                $description = $body->description ?? "";
                $fee_transaction = $body->fee_transaction ?? "";
                call_user_func_array([$this->controller, "AddCategoryTransaction"], [["name_transaction" => $name_transaction, "description" => $description, "fee_transaction" => $fee_transaction]]);
                //            create Category Transaction
            }
        } else if (get_class($this->controller) === "Cards") {
            if (isset($body->pin) && isset($body->status) && isset($body->id_customer)) {
                $pin = $body->pin ?? "";
                $status = $body->status ?? "";
                $id_customer = $body->id_customer ?? "";
                call_user_func_array([$this->controller, "AddCard"], [["id_customer" => $id_customer, "pin" => $pin, "status" => $status]]);
            } else if (isset($body->pin)) {
                call_user_func_array([$this->controller, "GetCardByCustomer"], [$body->id_customer]);
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
            if (get_class($this->controller) === "Branches") {
                if (isset($body->name_branch) || isset($body->location_branch)) {
                    $name = $body->name_branch ?? "";
                    $location = $body->location_branch ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("name" => $name, "location" => $location, "id" => $this->params[0])]);
                }
            } else if (get_class($this->controller) === "Cards") {
                if (isset($body->pin) || isset($body->status)) {
                    $pin = $body->pin ?? "";
                    $status = $body->status ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("pin" => $pin, "status" => $status, "id_card" => $this->params[0])]);
                }
            } else if (get_class($this->controller) === "Passbooks") {

                if (isset($body->id_customer) || isset($body->money) || isset($body->id_category_passbook)) {
                    $id_customer = $body->id_customer ?? "";
                    $money = $body->money ?? "";
                    $id_category_passbook = $body->id_category_passbook ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("id_customer" => $id_customer, "money" => $money, "id_category_passbook" => $id_category_passbook, "id_passbook" => $this->params[0])]);
                }
            } else if (get_class($this->controller) === "CategoryPassbooks") {

                if (isset($body->name_passbook) && isset($body->period) && isset($body->interest_rate) && isset($body->description)) {
                    $name_passbook = $body->name_passbook ?? "";
                    $period = $body->period ?? "";
                    $interest_rate = $body->interest_rate ?? "";
                    $description = $body->description ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("name_passbook" => $name_passbook, "period" => $period, "interest_rate" => $interest_rate, "description" => $description, "id_category_passbook" => $this->params[0])]);
                }
            } else if (get_class($this->controller) === "HistoryTransactions") {

                if (isset($body->id_category_transaction) || isset($body->money) || isset($body->id_customer) || isset($body->part_transaction) || isset($body->status)) {
                    $id_category_transaction = $body->id_category_transaction ?? "";
                    $money = $body->money ?? "";
                    $id_customer = $body->id_customer ?? "";
                    $part_transaction = $body->part_transaction ?? "";
                    $status = $body->status ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("id_category_transaction" => $id_category_transaction, "money" => $money, "id_customer" => $id_customer, "part_transaction" => $part_transaction, "status" => $status, "id_transaction" => $this->params[0])]);
                }
            } else if (get_class($this->controller) === "CategoryTransactions") {

                if (isset($body->name_transaction) && isset($body->description) && isset($body->fee_transaction)) {
                    $name_transaction = $body->name_transaction ?? "";
                    $description = $body->description ?? "";
                    $fee_transaction = $body->fee_transaction ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("name_transaction" => $name_transaction, "description" => $description, "fee_transaction" => $fee_transaction, "id_category_transaction" => $this->params[0])]);
                }
            } else if (get_class($this->controller) === "Customers") {
                if (isset($body->oldPassword) && isset($body->newPassword)) {

                    $newPass = $body->newPassword ?? "";
                    $oldPass = $body->oldPassword ?? "";
                    call_user_func_array([$this->controller, "changePassword"], [$oldPass, $newPass, $this->params[0]]);
                } else if (isset($body->name) || isset($body->citizen_identity_card) || isset($body->phone) || isset($body->mail) || isset($body->address) || isset($body->age) || isset($body->money) || isset($body->id_card) || isset($body->id_branch)) {

                    $name = $body->name ?? "";
                    $id_branch = $body->id_branch ?? "";
                    $citizen_identity_card = $body->citizen_identity_card ?? "";
                    $phone = $body->phone ?? "";
                    $mail = $body->mail ?? "";
                    $address = $body->address ?? "";
                    $age = $body->age ?? "";
                    $money = $body->money ?? "";
                    $id_card = $body->id_card ?? "";
                    call_user_func_array([$this->controller, "UpdateById"], [array("mail" => $mail, "address" => $address, "name" => $name, "id_branch" => $id_branch, "citizen_identity_card" => $citizen_identity_card, "age" => $age, "phone" => $phone, "money" => $money, "id_card" => $id_card, "id_person" => $this->params[0])]);
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