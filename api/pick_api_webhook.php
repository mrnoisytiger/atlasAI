<?php

	// Requires for extensions
	require 'api_extensions/weather.php';
	require 'functions/genRandString.php';
	
	// Get POST Data from Slack
/*	$token = $_POST['token'];
	$text = $_POST['text']; */
	
	// Use Debug GET Data
	$token = $_GET['token'];
	$text = $_GET['text'];
	
	// Set ENV Variables Needed
	$slack_token = $_ENV['SLACK_TOKEN'];
	$apiai_key = $_ENV['APIAI_KEY'];
	$query_url = "https://api.api.ai/v1/";
	$query_verion = "query?v=20161117";
	$session_id = genRandString();
	$timezone = "America/Los_Angeles";
	$lang = "en";
	
	if ( $token !== $slack_token ) {
		echo "Token Incorrect";
		die;
	}
	
	// Send text to process by API.AI, similar to client-side processing. Slack text is inserted via webhook so it is not processed and has to be processed by the server-side script.
	$process_data = array(
		'query' => $text,
		'lang' => $lang,
		'timezone' => $timezone,
		'sessionID' => $session_id
	);
	$process = curl_init( $query_url . $query_version );
		// Set curl options to prep for POST request
		curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($process, CURLOPT_POST, true);
		curl_setopt($process, CURLOPT_POSTFIELDS, $process_data);
		curl_setopt($process, CURLOPT_HTTPHEADER, array(
			'Authorization' => 'Bearer ' . $apiai_key,
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
	$intentObject = json_decode($intentObject);
	
	// Identical to pick_api.php to send to API extensions
	$intentAction = $intentObject['result']['action'];
	
	switch($intentAction) {

        case "getWeather":
            $result = extension_weather($intentObject);
            $slack_output = array(
            	'text' => $result,
            );
            echo json_encode($slack_output);
            break;

        default:
            echo "No API defined for this action.";

    }
	
?>