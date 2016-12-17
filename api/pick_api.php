<?php

// Requires
require 'api_extensions/weather.php';

    $intentObject = $_POST["intentObject"];

    $intentAction = $intentObject['result']['action'];

    switch($intentAction) {

        case "getWeather":
            $result = extension_weather($intentObject);
            echo $result;
            break;

        default:
            echo "No API defined for this action.";

    }

?>
