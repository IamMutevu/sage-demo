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

        $myfile = fopen("data.txt", "w") or die("Unable to open file!");
        $txt = json_encode($contact)."\n";
        fwrite($myfile, $txt);
        fclose($myfile);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://api.accounting.sage.com/v3.1/contacts");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json', 'Authorization:Bearer '.Authentication::getAccessToken()));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        $response = curl_exec($curl);
        curl_close($curl);

        self::linkContactRecord(json_decode($response), $client_id);
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

    private static function linkContactRecord($contact, $client_id){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("INSERT INTO `sage_contacts`(name, client_id, contact_id, created_at, updated_at) VALUES(?, ?, ?, ?, ?)");
        $query->execute(array($contact->displayed_as, $contact->id, $client_id, date("d-m-Y H:i"), date("d-m-Y H:i")));
        $connection = null;
    }

    private static function getClientData($client_id){
        $connection = DatabaseConnection::connect();
        $query = $connection->prepare("SELECT * FROM project_clients WHERE client_id = ?");
        $query->execute(array($client_id));
        $connection = null;
        $client = $query->fetch(PDO::FETCH_OBJ);

        $data = array(
            "name" => $client->client_name,
            "contact_type_ids" => array(
                "CUSTOMER"
            ),
            "main_address" => array(
                "city" => $client->client_home
            ),
            "main_contact_person" => array(
                "name" => $client->client_name,
                "job_title" => $client->wp_title,
                "telephone" => $client->wp_phone,
                "mobile" => $client->client_phone,
                "email" => $client->client_email,
                "is_main_contact" => true,
                "is_preferred_contact" => true
            )
        );

        return $data;
    }
}