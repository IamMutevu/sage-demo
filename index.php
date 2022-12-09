<?php
require_once 'classes/SageApi.php';
// header('Location: https://www.sageone.com/oauth2/auth/central?filter=apiv3.1&client_id=d79f512d-a5d3-49f7-a7d3-b1c54261670d/f6751f52-1d0b-4af1-b740-ad396d00faec&response_type=code&redirect_uri=https://localhost/sage-demo/callback.php');

$url = substr($_SERVER['SCRIPT_URL'], 21);
$route = explode("/", $url);
$direction = $route[0];

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

switch ($direction) {
    case 'callback':
        $sage->processCallback($params);
        break;
    
    default:
        # code...
        break;
}
