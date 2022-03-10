<?php
namespace App\Http\ShipEngine;

use App\Http\Shopify\ShipEngine;
use App\Settings;

class Rates extends ShipEngine{
	
	private $endpoint_url = '/rates/estimate';
	private $request_pattern = [
		"carrier_ids" => [],
		"from_country_code" => "CA",
		"from_postal_code" => "L3R 1B9",
		"to_country_code" => "",
		"to_state_province" => "",
		"to_postal_code" => "",
		"confirmation" => "none",
		"address_residential_indicator" => "no",
		"weight" => ["value" => 0, "unit" => "pound"],
	];
	
	public function getEstimateRates($params){
		$units = $params['units'];
		unset($params['units']);
		
		$this->request_pattern = array_merge($params, $this->request_pattern);
		$this->request_pattern['weight']['value'] = $units;
		
		$ship_engine_carrier_ids = Settings::get('ship_engine_carrier_ids');

		if(!empty($ship_engine_carrier_ids)){
			$this->request_pattern['carrier_ids'] = array_map('trim', explode(',', $ship_engine_carrier_ids));
		}
		
		$result = parent::post($this->endpoint_url, json_encode($this->request_pattern));
		
		return $result;
	}
	
}
