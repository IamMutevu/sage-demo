<?php

// include '../config.php';
require_once 'DatabaseConnection.php';
require_once 'Configuration.php';

class Authentication{
    public static function getAccessToken(){
        $stored_token_record = self::retrieveAccessToken();

        // Check if token has expired
        if(time() - strtotime($stored_token_record->updated_at) > 180){
            self::refreshAccessToken($stored_token_record->token);
        }

        $access_token = self::retrieveAccessToken()->token->access_token;

        return $access_token;
    }

    public static function requestAccessToken($code, $user_id){
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

        self::storeAccessToken($user_id, $access_token);
    }

    public static function retrieveAccessToken(){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("SELECT access_tokens.access_token, access_tokens.updated_at  FROM access_tokens WHERE app = ?");
        $query->execute(array("sage"));
        $connection = null;
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public static function refreshAccessToken($stored_token){
        Configuration::configure();
        
        $parameters = array(
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'grant_type' => 'refresh_token',
            'refresh_token' => $stored_token->refresh_token,
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://oauth.accounting.sage.com/token");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
        $access_token = curl_exec($curl);
        curl_close($curl);

        self::updateStoredAccessToken($access_token);
    }

    private function storeAccessToken($user_id, $access_token){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("INSERT INTO `access_tokens`(token, user_id, app, created_at, updated_at) VALUES(?, ?, ?, ?, ?)");
        $query->execute(array(json_encode($access_token), $user_id, "sage", date("d-m-Y H:i"), date("d-m-Y H:i")));
        $connection = null;
    }

    private function updateStoredAccessToken($access_token){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("UPDATE `access_tokens` SET token = ?, updated_at = ? WHERE app = ? ");
        $query->execute(array($access_token, date("d-m-Y H:i"), "sage"));
        $connection = null;
    }
}