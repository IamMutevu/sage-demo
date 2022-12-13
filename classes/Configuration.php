<?php

class Configuration{
    public static function configure(){
        if(file_exists($_SERVER['DOCUMENT_ROOT'].'/apps/sage/env/config.json')){
            $configs = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/apps/sage/env/config.json'));
        
            define("CLIENT_ID", $configs->client_id);
            define("CLIENT_SECRET", $configs->client_secret);
            define("REDIRECT_URI", $configs->redirect_uri);
        
        }
        else{
            echo "Configuration file missing";
            exit;
        }
    }
}