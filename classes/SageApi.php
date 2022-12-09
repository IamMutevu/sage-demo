<?php
require_once 'Authentication.php';

class SageApi{
    public function processCallback($params){
        if(!isset($params['error'])){
            if(isset($params['code'])){
                $code = $params['code'];

                $auth = new Authentication();
                $auth->getAccessToken($code);
            }

            header('Location: /?settings/accounts?success=Integrated successfully');
        }
        else{
            switch ($params['error']) {
                case 'access_denied':
                    $error = "Access denied";
                    break;
                
                default:
                    $error = "An error occurred";
                    break;
            }

            header('Location: /?settings/accounts?error='.$error);
        }
    }
}