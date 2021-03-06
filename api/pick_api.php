<?php

	// Requires: Remember to keep in sync with the Webhook Pick API file
	require 'api_extensions/weather.php';
	require 'api_extensions/stock_info.php';
	require 'functions/genRandString.php';
	require 'functions/slackOutput.php';
	require 'functions/removeDoubleBslash.php';

	$envvars = parse_ini_file ( "config/config.ini" );
		
    $intentObject = $_POST["intentObject"];

    $intentAction = $intentObject['result']['action'];

    switch($intentAction) {

		// Do not need a case for SmallTalk as that's handled Client-side to save network requests
		
		case "getStockInfo":
			$result = extension_stockInfo($intentObject, false);
			echo $result;
			break;
		
        case "getWeather":
            $result = extension_weather($intentObject);
            echo str_replace("_", "", $result); // Remove the underscores used for Slack formatting
            break;

		case "input.unknown";
			$result = "Sorry! I can't do that!";
			echo $result;
            break;
            
        default:
            echo "No API defined for this action.";

    }

?>
