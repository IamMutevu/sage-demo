<?php

if(file_exists('env/config.json')){
    $configs = json_decode(file_get_contents('env/config.json'));

    define("CLIENT_ID", $configs->client_id);
    define("CLIENT_SECRET", $configs->client_secret);
    define("REDIRECT_URI", $configs->redirect_uri);

}
else{
    echo "Configuration file missing";
    exit;
}