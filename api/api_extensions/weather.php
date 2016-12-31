<?php

// Requires
require 'language_structures/weather_conditions.php';

    function extension_weather($intentObject) {
    	
    	$envvars = parse_ini_file( "config/config.ini" );
    	$api_key = $envvars['OPENWEATHERMAP_KEY'];

        $intentObjectResults = $intentObject['result']['parameters'];

        if ( $intentObjectResults['geo-city'] == "" ) {
            $weather_location = "Los Angeles";
        } else {
            $weather_location = $intentObject['result']['parameters']['geo-city'];
        }

        if ( $intentObjectResults['date'] == "" ) {
            $weather_date = date('Y-m-d');
            $current_date = true;
        } else {
            $weather_date = $intentObjectResults['date'];
            if ( $weater_date !== date('Y-m-d') ) {
                $current_date = false;
            } else {
                $current_date = true;
            }
        }

        if ( $intentObjectResults['time'] == "" ) {
            $weather_date = date('H:i:s');
            $current_time = true;
        } else {
            $weater_date = $intentObjectResults['time'];
            $current_time = false;
        }

        if ( !$current_date || !$current_time ) {
            $current = false;
        } else {
            $current = true;
        }

        if ( $current = true ) {
            $api_url = "http://api.openweathermap.org/data/2.5/find?q=" . htmlspecialchars($weather_location) . ",us" . "&units=imperial&appid=" . $api_key;

            $full_weather = json_decode(file_get_contents($api_url));

            switch($intentObjectResults['weather_type']) {

                case "temperature":
                    $temp = round($full_weather->list[0]->main->temp);
                    return "The current temperature in $weather_location is $temp degrees.";
                    break;

                case "pressure":
                    $pressure = $full_weather->list[0]->main->pressure;
                    return "The current pressure in $weather_location is $pressure.";
                    break;

                case "conditions":
                    $conditions = $full_weather->list[0]->weather[0]->id;
                    return language_weather_conditions( $conditions );
                    break;

                default:
                    return "Weather type not available yet!";
            }
        }

    }

?>