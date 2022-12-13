<?php
require_once 'classes/SageApi.php';

// Get parameters of incoming requests
if (!empty($_POST)) {
    $params = $_POST;

} elseif (!empty($_GET)) {
    $params = $_GET;
} else {
    $params = json_decode(file_get_contents("php://input"), true);
}

// Create SageApi object
$sage = new SageApi();

if($params['code']){
    $sage->processCallback($params);
}

