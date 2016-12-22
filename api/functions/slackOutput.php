<?php 

	function slackOutput( $output, $type = "text" ) {
		
		if ( $type == "text" ) {
			$slack_output = array(
            	'text' => $output,
    		);
		} elseif ( $type == "attachment" ) {
			$slack_output = array(
            	'attachments' => $output,
        	);
		}

        return json_encode($slack_output);
		
	}

?>