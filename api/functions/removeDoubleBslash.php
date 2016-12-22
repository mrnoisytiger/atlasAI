<?php

	function removeDoubleBslash( $str ) {
		// Damn string escaping is hard and confusing. Replaces double backslashes with a single backslash. Useful if doing new-lines on Slack that expects a single backslash.
		$str = str_replace('\\\\', '\\', $str);
		return $str;
	}

?>