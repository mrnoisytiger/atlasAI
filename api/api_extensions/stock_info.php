<?php

	function extension_stockInfo($intentObject) {

		// Set Variables from intentObject		
		$intentObjectResults = $intentObject['result']['parameters'];
		
		if ( $intentObjectResults['date'] == "" ) {
			$stock_date = "none";
		} else {
			$stock_date = htmlspecialchars($intentObjectResults['date']);
		}
		
		$stock_name = htmlspecialchars_decode($intentObjectResults['stockName']);
		
		$stock_info_type = $intentObjectResults['stockInfoType'];
		
		// Do the initial lookup of Stock Information
		$stock_full_results = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=' . htmlspecialchars(urlencode($stock_name)));
		$stock_full_results = json_decode($stock_full_results, true);
		
		// If the user inputs a stock name instead of ticker symbol, lookup list of possible companies and symbosl and return that instead in a nicely formatted list
		if ( !empty($stock_full_results['Message'])  ) {
			// Lookup the stock symbol
			$stock_symbols = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=' . htmlspecialchars(urlencode($stock_name)));
			$stock_symbols = json_decode($stock_symbols);
			// Bootstrap a Slack Attachment for prettier representation
			$company_list = array(
				array(
					'fallback' => "Here are some possible company symbols",
					'pretext' => "Please use a _*valid ticker symbol*_. Here are some possible symbols based on your entry.",
					'fields' => array(),
					'color' => "#FFC966",
					"mrkdwn_in"=> ["text", "pretext", "fields"],
				),
			);
			
			// Append Company infomation to Company List
				// Counter for possible company matches
				$i = 0;
			// Loop through Lookup result and add the symbols and company names to the $company_list attachment in fields
				// Add the Symbol then the company Name as short-fields so they can appear side-by-side
			foreach ( $stock_symbols as $company ) {
				$company_symbol = array(
					'title' => "Symbol",
					'value' => $company->Symbol,
					'short' => true,
				);
				array_push($company_list[0]['fields'], $company_symbol);
				
				$company_name = array(
					'title' => "Company Name",
					'value' => "_" . $company->Name . "_",
					'short' => true,
				);
				// If the company name manages to be blank sometimes, pop off the symbol so we don't have duplicates. Otherwise, push it into the array
				if ($company_name['value'] !== "__") {
					array_push($company_list[0]['fields'], $company_name);
				} else {
					array_pop($company_list[0]['fields']);
				}
				
				// Only allow 5 lookups total
				if (++$i === 5) { break; }
				
				$company_list[0]['fields'] = array_unique($company_list[0]['fields'], SORT_REGULAR);
			}
			
			// Return an array with Output and TRUE if an attachment is necessary
			return array($company_list, true);
			
		} else { // The Stock Quote info lookup was successful. 
				// Set Name Mappings from NLP to API
				$api_mappings = array(
					'price' => 'LastPrice',
					'change' => 'Change',
					'pchange' => 'ChangePercent',
					'market cap' => 'MarketCap',
					'volume' => 'Volume',
					'high' => 'High',
					'low' => 'Low',
					'opening' => 'Open',
					'changeYTD' => 'ChangeYTD',
				);
				
				// Set Name Mappings from NLP to text output
				$text_mappings = array(
					'price' => 'current price',
					'change' => 'change for the day',
					'pchange' => 'percentage change so far',
					'market cap' => 'market cap',
					'volume' => 'trading volume',
					'high' => 'high for the day',
					'low' => 'low for the day',
					'opening' => 'opening price for today',
					'changeYTD' => 'year-to-date change',
				);
				
				// Set Array for types which take a dollar value
				$dollar_types = array( 'price', 'change', 'market cap', 'high', 'low', 'opening', 'changeYTD');
				// Set Array for types which take a dollar value
				$percentage_types = array( 'pchange' );
				// Set Array for types which take are in "shares"
				$shares_types = array( 'volume' );
				
			// If NLP didn't find a request for stock information
			if ( sizeof($stock_info_type) == 0 ) {
				return "Please specify information you would like to know.";
			} elseif ( sizeof($stock_info_type) == 1 ) { // If the user requests only a single type of information, simply give them some text
				$stock_info_type = $stock_info_type[0];
				// Covert the NLP stock info type into API's format
				$api_mapped_info_type = $api_mappings[$stock_info_type];
				// Covert the NLP stock into type into human grammer format
				$text_mapped_info_type = $text_mappings[$stock_info_type];
				
				// Provide units for the response based on the stock information requested
				if ( in_array($stock_info_type, $dollar_types)  ) {
					$result = 'The ' . $text_mapped_info_type . ' for ' . $stock_name . ' is $'. number_format($stock_full_results[$api_mapped_info_type], 2) . ".";
				} elseif ( in_array($stock_info_type, $percentage_types) ) {
					$result = 'The ' . $text_mapped_info_type . ' for ' . $stock_name . ' is '. number_format($stock_full_results[$api_mapped_info_type], 2) . "%.";
				} elseif ( in_array($stock_info_type, $shares_types) ) {
					$result = 'The ' . $text_mapped_info_type . ' for ' . $stock_name . ' is '. number_format($stock_full_results[$api_mapped_info_type], 0) . " shares.";
				}
			
				return array($result, false);
			
			} elseif ( sizeof($stock_info_type) > 1 ) { // If the user requests more than one type of information, give them a Slack attachment with the info.
				
				// Create a Slack attachment with fields ready to be populated.
				$stock_info_list = array(
					array(
						'fallback' => "Requested Data for " . $stock_name,
						'pretext' => "Here's the information you requested for _" . $stock_full_results['Name'] . "_",
						'fields' => array(),
						'color' => "#6AFB66",
						"mrkdwn_in"=> ["text", "pretext", "fields"],
					),
				);
				
				// For each piece of info requested in the array, iterate through it and insert it into the Slack Attachment as a field
				foreach( $stock_info_type as $type ) {

					// Convert NLP info type into API info type
					$api_mapped_info_type = $api_mappings[$type];
					// Convert NLP info type into human grammer text
					$text_mapped_info_type = $text_mappings[$type];
					
					// Append units based on necessary units
					if ( in_array($type, $dollar_types)  ) {
						$result = "$" . number_format($stock_full_results[$api_mapped_info_type], 2);
					} elseif ( in_array($type, $percentage_types) ) {
						$result = number_format($stock_full_results[$api_mapped_info_type], 2) . "%";
					} elseif ( in_array($type, $shares_types) ) {
						$result = number_format($stock_full_results[$api_mapped_info_type], 0) . " shares";
					}
	
					// Set up a field with the correct info. Capitalize the first letter of the Title string
					$type_info = array(
						'title' => ucfirst($type),
						'value' => $result,
						'short' => true,
					);
					
					// Add the field into the main array
					array_push( $stock_info_list[0]['fields'], $type_info );

				}
			
				return array($stock_info_list, true);
			}
		}
		
	}

?>