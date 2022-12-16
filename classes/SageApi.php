<?php
require_once 'Authentication.php';
require_once 'Contacts.php';
require_once 'Configuration.php';
Configuration::configure();

class SageApi{
    public function processCallback($params){
        if(!isset($params['error'])){
            if(isset($params['code'])){
                $code = $params['code'];
                $user_id = $params['user_id'];

                Authentication::requestAccessToken($code, $user_id);
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
        $token = Authentication::retrieveAccessToken();

        if($token != false){
            return true;
        }
        else{
            return false;
        }

    }

    public function createContact($client_id){
        Contacts::createContact($client_id);
    }
}