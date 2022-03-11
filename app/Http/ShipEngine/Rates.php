<?php
namespace App\Http\ShipEngine;

use App\Settings;
use Illuminate\Support\Arr;

class Rates extends ShipEngine{
	
	private $endpoint_url = '/rates/estimate';
	private $request_pattern = [
		"carrier_ids" => [],
		"from_country_code" => "CA",
		"from_state_province" => "ON",
		"from_postal_code" => "L3R 1B9",
		"to_country_code" => "",
		"to_state_province" => "",
		"to_postal_code" => "",
		"confirmation" => "none",
		"address_residential_indicator" => "no",
		"weight" => ["value" => 0, "unit" => "pound"],
	];
	private $lbs_per_units = ['low' => 0.5, 'high' => 1];
	
	public function getEstimateRates($params){
		$results = [];
		
		$config = config('app.shipengine');
		$ship_engine_carrier_ids = Settings::get('ship_engine_carrier_ids');
		
		if(!empty($ship_engine_carrier_ids)){
			$this->request_pattern['carrier_ids'] = array_map('trim', explode(',', $ship_engine_carrier_ids));
		}else{
			return ['raw' => $results, 'html' => ''];
		}

		$from_country_code = Settings::get('ship_engine_from_country_code');
		$from_state_province = Settings::get('ship_engine_from_state_province');
		$from_postal_code = Settings::get('ship_engine_from_postal_code');

		$this->request_pattern['from_country_code'] = !is_null($from_country_code) ? $from_country_code : $config['SHIPENGINE_FROM_COUNTRY_CODE'];
		$this->request_pattern['from_state_province'] = !is_null($from_state_province) ? $from_state_province : $config['SHIPENGINE_FROM_STATE_PROVINCE'];
		$this->request_pattern['from_postal_code'] = !is_null($from_postal_code) ? $from_postal_code : $config['SHIPENGINE_FROM_POSTAL_CODE'];;
		
		$this->request_pattern['to_country_code'] = $params['to_country_code'];
		$this->request_pattern['to_state_province'] = $params['to_state_province'];
		$this->request_pattern['to_postal_code'] = $params['to_postal_code'];
		
		
		foreach($this->lbs_per_units as $k => $lbs){
			$this->request_pattern['weight']['value'] = $params['units'] * $lbs;
			$result = $this->post($this->endpoint_url, json_encode($this->request_pattern));
			$results[$k] = $result;
			sleep(1);
		}
		
		return ['raw' => $results, 'html' => $this->toHtml($results)];
	}
	
	private function toHtml($data){
		$html = '';
		
		#dd($data);
		$data = $this->formatResults($data);
		#dd($data);
		
		if(!empty($data)){
			$rows = [];
			foreach($data as $id => $item){
				if(!empty($item['error_messages'])){
					$rows[] = sprintf('<tr><td colspan="4" class="text-center">%s</td></tr>', implode(PHP_EOL, $item['error_messages']));
				}else{
					$rows[] = sprintf('<tr id="%s"><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
						$id,
						$item['service_type'],
						$item['delivery_days'],
						isset($item['low']) ? $item['low'] : '-',
						isset($item['high']) ? $item['high'] : '-',
					);
				}
			}
			$html = implode(PHP_EOL, $rows);
		}
		
		return $html;
	}
	
	private function formatResults($data){
		$results = [];
		
		foreach($data as $lbs_type => $items){
			if(empty($items)) continue;
			
			foreach($items as $item){
				$sc = $item['service_code'];
				
				if(!isset($results[$sc])){
					$results[$sc] = [];
				}
				
				$delivery_days = intval($item['delivery_days']);
				
				$results[$sc]['error_messages'] = $item['error_messages'];
				$results[$sc]['service_type'] = $item['service_type'];
				#$results[$sc]['carrier_code'] = $item['carrier_code'];
				#$results[$sc]['carrier_nickname'] = $item['carrier_nickname'];
				$results[$sc]['delivery_days'] = sprintf('%d day%s', $delivery_days, ($delivery_days == 1 ? '' : 's'));
				$results[$sc][$lbs_type] = isset($item['shipping_amount']['amount']) ? sprintf('$%s', $item['shipping_amount']['amount']) : '-';
			}
		}
		
		return $results;
	}
	
}
