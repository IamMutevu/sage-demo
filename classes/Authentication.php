<?php
require_once 'DatabaseConnection.php';

class Authentication{
    public function getAccessToken(){


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