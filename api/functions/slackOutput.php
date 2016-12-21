<?php 

	function slackOutput( $output ) {
		
		$slack_output = array(
            	'text' => $output,
        );
        echo json_encode($slack_output);
		
	}

?>