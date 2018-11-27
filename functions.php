<?php

function config($key = 'app')
{
    $appConfig = require APP_CONFIG_PATH . "/App.php";
    $databaseConfig = require APP_CONFIG_PATH . "/Database.php";
    // $split = explode(".", trim($key));
    // $config = trim($split[0]) . "Config";
    switch ($key) {
        case "app":
        case "appConfig":
            return $appConfig;
            break;
        case "db":
        case "database":
        case "databaseConfig":
            return $databaseConfig;
            break;
        default:
            return $appConfig;
            break;
    }
}
