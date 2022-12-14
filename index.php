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

// Add user ids to params array. How it's added here assumes that it exists as a declared variable prior to the inclusion of this script
$params['user_id'] = $user_id;

// Create SageApi object
$sage = new SageApi();

if(isset($params['code'])){
    $sage->processCallback($params);
}

