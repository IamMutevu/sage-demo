<?php

require_once '../config.php';
require_once 'DatabaseConnection.php';

class Authentication{
    public function getAccessToken($code){
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
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        $response = curl_exec($curl);
        curl_close($curl);

        echo $response;
    }

    private function storeAccessToken($user_id, $access_token, $code){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("INSERT INTO `user_access_tokens`(access_token, code, user_id, created_at, updated_at) VALUES(?, ?, ?, ?, ?)");
        $query->execute(array(json_encode($access_token), $code, $user_id, date("d-m-Y H:i"), date("d-m-Y H:i")));
        $connection = null;
    }

    private function retrieveAccessToken(){
        
    }
}