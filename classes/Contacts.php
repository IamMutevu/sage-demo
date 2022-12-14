<?php

require_once 'DatabaseConnection.php';
require_once 'Authentication.php';

/**
 * This class helps in the creation of contacts. Contacts can be either VENDOR or CUSTOMER
 */

class Contacts{
    public static function createContact($client_id){
        $data = self::getClientData($client_id);

        $contact = array(
            "contact" => $data
        );
        $parameters = json_encode($contact);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.accounting.sage.com/v3.1/contacts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'Authorization:Bearer '.Authentication::getAccessToken()));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        $response = curl_exec($curl);
        curl_close($curl);

        self::linkContactRecord($response);
    }

    public function updateContact($data){
        $contact = array(
            "contact" => $data
        );
        $parameters = json_encode($contact);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.accounting.sage.com/v3.1/contacts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        $response = curl_exec($curl);
        curl_close($curl);

        $this->linkContactRecord($response);
    }

    private function linkContactRecord($contact){
        // $connection = DatabaseConnection::connect();
        // $query = $connection->prepare("INSERT INTO `access_tokens`(access_token, user_id, app, created_at, updated_at) VALUES(?, ?, ?, ?, ?)");
        // $query->execute(array(json_encode($access_token), $user_id, "sage", date("d-m-Y H:i"), date("d-m-Y H:i")));
        // $connection = null;
    }

    private function getClientData($client_id){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("SELECT * FROM project_clients WHERE client_id = ?");
        $query->execute(array($client_id));
        $connection = null;
        $client = $query->fetch(PDO::FETCH_OBJ);

        $data = array(
            "name" => $client->client_name,
            "contact_type_ids" => array([
                "CUSTOMER"
            ]),
            "main_address" => array(
                "city" => $client->client_home
            )
        );

        return $data;
    }
}