<?php
require_once 'Authentication.php';

class SageApi{
    public function processCallback($params){
        if(!isset($params['error'])){
            if(isset($params['code'])){
                $code = $params['code'];
                $user_id = $params['user_id'];

                $auth = new Authentication();
                $auth->getAccessToken($code, $user_id);
            }

            header('Location: /admin/index.php?settings/accounts&success=Integrated successfully');
            exit;
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

    public function isAuthenticated(){
        $auth = new Authentication();
        $token = $auth->retrieveAccessToken();

        if(count($token) > 0){
            $auth->refreshAccessToken();
            return true;
        }
        else{
            return false;
        }
    }
}