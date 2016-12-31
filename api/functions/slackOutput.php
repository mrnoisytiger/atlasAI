<?php 

	function slackOutput( $output, $type = "text" ) {
		$envvars = parse_ini_file( "config/config.ini" );
		$response_url = $envvars['SLACK_RESPONSE_URL'];
		
		echo $response_url;
		
		if ( $type == "text" ) {
			$slack_output = array(
            	'text' => $output,
    		);
		} elseif ( $type == "attachment" ) {
			$slack_output = array(
            	'attachments' => $output,
        	);
		}
		
		$response = curl_init( $response_url );
		
		curl_setopt($response, CURLOPT_POST, true);
		curl_setopt($response, CURLOPT_POSTFIELDS, json_encode($slack_output));
		curl_setopt($response, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json'
		));
		
		curl_exec($response);
		curl_close($response);
		
	}

?>