<?php

	function extension_stockInfo($intentObject) {

		// Set Variables from intentObject		
		$intentObjectResults = $intentObject['result']['parameters'];
		
		if ( $intentObjectResults['date'] == "" ) {
			$stock_date = "none";
		} else {
			$stock_date = htmlspecialchars($intentObjectResults['date']);
		}
		
		$stock_name = htmlspecialchars($intentObjectResults['stockName']);
		
		$stock_info_type = htmlspecialchars($intentObjectResults['stockInfoType']);
		
		// Do the initial lookup of Stock Information
		$stock_full_results = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=' . $stock_name);
		$stock_full_results = json_decode($stock_full_results, true);
		
		// If the user inputs a stock name instead of ticker symbol, lookup list of possible companies and symbosl and return that instead in a nicely formatted list
		if ( $stock_full_results['Message'] == "No symbol matches found for " . $stock_name . ". Try another symbol such as MSFT or AAPL, or use the Lookup API." ) {
			$stock_symbols = file_get_contents('http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=' . $stock_name);
			$stock_symbols = json_decode($stock_symbols);
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
			$i = 0;
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
				if ($company_name['value'] !== "__") {
					array_push($company_list[0]['fields'], $company_name);
				} else {
					array_pop($company_list[0]['fields']);
				}
				
				if (++$i === 5) { break; }
			}
			
			return $company_list;
		}
		
	}

?>