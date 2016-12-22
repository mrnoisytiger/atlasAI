<?php

	function extension_stockInfo($intentObject) {

		// Set Variables from intentObject		
		$intentObjectResults = $intentObject['result']['parameters'];
		
		if ( $intentObjectResults['date'] == "" ) {
			$stock_date = "none";
		} else {
			$stock_date = htmlspecialchars($intentObjectResults['date']);
		}
		
		$stock_name = $intentObjectResults['stockName'];
		
		$stock_info_type = $intentObjectResults['stockInfoType'];
		
		// Do the initial lookup of Stock Information
		$stock_full_results = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=' . $stock_name);
		$stock_full_results = json_decode($stock_full_results, true);
		
		// If the user inputs a stock name instead of ticker symbol, lookup list of possible companies and symbosl and return that instead in a nicely formatted list
		if ( $stock_full_results['Message'] == "No symbol matches found for " . $stock_name . ". Try another symbol such as MSFT or AAPL, or use the Lookup API." ) {
			// Lookup the stock symbol
			$stock_symbols = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=' . $stock_name);
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
			}
			
			// Return an array with Output and TRUE if an attachment is necessary
			return array($company_list, true);
			
		} else {
			// The Stock Quote info lookup was successful. 
				// Set Name Mappings from NLP to API
				$nlp_mappings = array(
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
					'opening' => 'openning price for today',
					'changeYTD' => 'year-to-date change',
				);
			
			if ( sizeof($stock_info_type) == 0 ) {
				return "Please specify information you would like to know.";
			} elseif ( sizeof($stock_info_type == 1) ) {
				$stock_info_type = $stock_info_type[0];
				$nlp_mapped_info_type = $nlp_mappings[$stock_info_type];
				$text_mapped_info_type = $text_mappings[$stock_info_type];
				$result = 'The ' . $text_mapped_info_type . ' for ' . $stock_name . ' is '. round($stock_full_results[$nlp_mapped_info_type], 2);
			
				return array($result, false);
			} else {
				
				$stock_info_list = array(
				array(
					'fallback' => "Requested Data for " . $stock_name,
					'pretext' => "Here's the information you requested for " . $stock_name,
					'fields' => array(),
					'color' => "#FFC966",
					"mrkdwn_in"=> ["text", "pretext", "fields"],
				),
				

			);
				
			}
		}
		
	}

?>