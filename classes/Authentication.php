<?php

// include '../config.php';
require_once 'DatabaseConnection.php';
require_once 'Configuration.php';

class Authentication{
    public function getAccessToken($code, $user_id){
        Configuration::configure();
        
        $parameters = array(
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => REDIRECT_URI,
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://oauth.accounting.sage.com/token");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $access_token = curl_exec($curl);
        curl_close($curl);

        $this->storeAccessToken($user_id, $access_token);
    }

    private function storeAccessToken($user_id, $access_token){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("INSERT INTO `access_tokens`(access_token, user_id, app, created_at, updated_at) VALUES(?, ?, ?, ?, ?)");
        $query->execute(array(json_encode($access_token), $user_id, "sage", date("d-m-Y H:i"), date("d-m-Y H:i")));
        $connection = null;
    }

    public function retrieveAccessToken(){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("SELECT access_tokens.access_token FROM access_tokens WHERE app = ?");
        $query->execute(array("sage"));
        $connection = null;
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function refreshAccessToken(){

    }
}