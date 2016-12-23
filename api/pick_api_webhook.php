<?php

	// Requires for extensions
	require 'api_extensions/weather.php';
	require 'api_extensions/stock_info.php';
	require 'functions/genRandString.php';
	require 'functions/slackOutput.php';
	require 'functions/removeDoubleBslash.php';
	
	// If debugging is set to true, use the info from a GET query instead of the POST query so it's easier to test
	if ( $_GET['debug'] == 'true' ) {
		// Use Debug GET Data
		$token = $_GET['token']; 
		$text = $_GET['text']; 
		$text = htmlspecialchars_decode($text); 
	} else {
		// Get POST Data from Slack 
		$token = $_POST['token'];
		$text = $_POST['text']; 
		$user_id = $_POST['user_id'];
	}
	
	### PROBABLY NOT NEEDED BECAUSE OF USING USER ID AUTHENTICATION ###
	/*
	// Normalize Text Query
		$trigger_words = array(
			"hey atlas",
			"hi atlas",
			"hi",
			"hey",
			".",
		);
		// Take any "trigger words" out of the command string and remove any starting/trailing white-space
		$text = trim(str_ireplace($trigger_words, "", $text)); 
	*/
	
	// Set environment variables as needed
	$envvars = parse_ini_file( "config/config.ini" );
	$slack_token = $envvars['SLACK_TOKEN'];
	$apiai_key = $envvars['APIAI_KEY'];
	$valid_user_ids = $envvars['VALID_USER_IDS'];
	$query_url = "https://api.api.ai/v1/";
	$query_version = "query?v=20161117";
	$session_id = genRandString(10);
	$timezone = "America/Los_Angeles";
	$lang = "en";
	
	// Check to make sure token does come from the correct Slack webhook
	if ( $token !== $slack_token ) {
		return "Token Incorrect";
		die;
	}
	
	if ( $_GET['debug'] !== 'true' && !in_array($user_id, $valid_user_ids)) {
		return "User not Authenticated";
		die;
	}
	
	// Send text to process by API.AI, similar to client-side processing. Slack text is inserted via webhook so it is not processed and has to be processed by the server-side script.
	$process_data = array(
		"query" => $text,
		"lang" => $lang,
		"timezone" => $timezone,
		"sessionId" => $session_id
	);
	$process = curl_init( $query_url . $query_version );
		
		// Set curl options to prep for POST request
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($process, CURLOPT_POST, true);
		curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($process_data));
		curl_setopt($process, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $apiai_key,
			'Content-Type: application/json; charset=utf-8'
		));
		// End set curl options
		
	$intentObject = curl_exec($process);
		
		// Check if curl errored out
		if ($curl_response === false) {
	    	$info = curl_getinfo($curl);
	    	curl_close($curl);
	    	die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		
	curl_close($process); 

	// Turn JSON from API.AI into an associative array for access with the bracket syntax
	$intentObject = json_decode($intentObject, true);
	
	// Identical to pick_api.php to send to API extensions
	$intentAction = $intentObject['result']['action'];
	
		// Action Normalization happens here
		if ( substr($intentAction, 0, 9) == "smalltalk" ) {
    		$intentAction = "smalltalk";
		}
		
	switch($intentAction) {

		case "smalltalk":
			$result = $intentObject['result']['fulfillment']['speech'];
			echo slackOutput($result);
			break;
			
        case "getWeather":
            $result = extension_weather($intentObject);
            echo slackOutput($result);
            break;
            
        case "getStockInfo":
        	$result = extension_stockInfo($intentObject, true); // Since the extension uses extensions, it's important to differentiate requests from the WebUI and the Slack interface. 
        	if ( $result[1] == true ) {
        		$result = (string)slackOutput($result[0], "attachment");
        	} else {
        		$result = (string)slackOutput($result[0], "text");
        	}
        	echo $result;
        	break;
        	

		case "input.unknown";
			$result = "Sorry! I can't do that!";
			echo slackOutput($result);
            break;
            
        default:
            $result = "No API defined for this action.";
            echo slackOutput($result);
			break;
			
    }
	
?>