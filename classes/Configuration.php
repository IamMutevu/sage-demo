<?php

class Configuration{
    public static function configure(){
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/sage/env/config.json')){
            $configs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/apps/sage/env/config.json'));
        
            define("CLIENT_ID", $configs->client_id);
            define("CLIENT_SECRET", $configs->client_secret);
            define("REDIRECT_URI", $configs->redirect_uri);
            define("SERVER_NAME", $configs->server_name);
            define("DATABASE", $configs->database);
            define("DB_USER", $configs->db_user);
            define("DB_PASSWORD", $configs->db_password);
        
        }
        else{
            echo "Configuration file missing";
            exit;
        }
    }
}