<?php
$config = parse_ini_file('Config.init');
$app_path = "./src/core/App.php";
$base_controller = "./src/api/" . $config["version"] . "/controllers/Controller.php";
$base_model = "./src/api/" . $config["version"] . "/models/DB.php";
// Process URL from browser
require_once "./src/core/App.php";

// How controllers call Views & Model
if (file_exists($base_controller)) {
    require_once $base_controller;
}

if (file_exists($base_model)) {
    require_once $base_model;
}
?>