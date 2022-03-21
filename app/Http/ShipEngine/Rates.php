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
		"to_country_code" => "CA",
		"to_state_province" => "",
		"to_postal_code" => "",
		"confirmation" => "none",
		"address_residential_indicator" => "no",
		"weight" => ["value" => 0, "unit" => "pound"],
	];
	private $lbs_per_units = ['low' => 0.5, 'high' => 1];
	private $se_settings = [];
	private $admin_view = false;
	
	public function setAdminView($value){
		$this->admin_view = $value;
	}
	
	public function getEstimateRates($params){
		$results = [];
		$config = config('app.shipengine');
		$this->getServiceOptions();
		
		if(!empty($this->se_settings['carrier_ids'])){
			$this->request_pattern['carrier_ids'] = $this->se_settings['carrier_ids'];
		}else{
			return ['raw' => $results, 'html' => ''];
		}

		$this->request_pattern['from_country_code'] = !is_null($this->se_settings['from_country_code']) ? $this->se_settings['from_country_code'] : $config['SHIPENGINE_FROM_COUNTRY_CODE'];
		$this->request_pattern['from_state_province'] = !is_null($this->se_settings['from_state_province']) ? $this->se_settings['from_state_province'] : $config['SHIPENGINE_FROM_STATE_PROVINCE'];
		$this->request_pattern['from_postal_code'] = !is_null($this->se_settings['from_postal_code']) ? $this->se_settings['from_postal_code'] : $config['SHIPENGINE_FROM_POSTAL_CODE'];;
		
		$this->request_pattern['to_country_code'] = isset($params['to_country_code']) ? $params['to_country_code'] : $this->se_settings['to_country_code'];
		$this->request_pattern['to_state_province'] = $params['to_state_province'];
		$this->request_pattern['to_postal_code'] = $params['to_postal_code'];
		
		
		foreach($this->lbs_per_units as $k => $lbs){
			$this->request_pattern['weight']['value'] = $params['units'] * $lbs;
			$result = $this->post($this->endpoint_url, json_encode($this->request_pattern));
			if(isset($result['errors'])){
				$results = $result;
			}else{
				$results[$k] = $result;
				sleep(1);
			}
		}
		
		return ['raw' => $results, 'html' => $this->toHtml($results)];
	}
	
	private function toHtml($data){
		$html = '';
		$rows = [];
		
		if(isset($data['errors'])){
			foreach($data['errors'] as $error){
				if($error['field_name'] == 'to_postal_code'){
					$error['message'] = 'Invalid "Country code" or "State Province code" or "Postal code".';
				}
				$rows[] = sprintf('<tr><td colspan="4" class="text-center error warning">%s</td></tr>', str_replace('_', ' ', $error['message']));
			}
		}else{
			$data = $this->formatResults($data);
			
			if(!empty($data)){
				foreach($data as $id => $item){
					if(!empty($item['error_messages'])){
						$rows[] = sprintf('<tr><td colspan="4" class="text-center error info">%s</td></tr>', implode(PHP_EOL, $item['error_messages']));
					}else{
						if($this->admin_view){
							$item['service_type'] .= '<br><small class="font-red">'.$id.'</small>';
						}
						$rows[] = sprintf('<tr id="%s"><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>',
							$id,
							$item['service_type'],
							$item['delivery_days'],
							isset($item['low']) ? $item['low'] : '-',
							isset($item['high']) ? $item['high'] : '-'
						);
					}
				}
			}
		}
		
		if(!empty($rows))
			$html = implode(PHP_EOL, $rows);

		return $html;
	}
	
	private function formatResults($data){
		$results = $this->addPickupData();
		
		foreach($data as $lbs_type => $items){
			if(empty($items)) continue;
			
			foreach($items as $item){
				$sc = trim($item['service_code']);
				
				if($this->se_settings['display_only_specific_services']){
					if(!isset($this->se_settings['services_options'][$sc])){
						continue;
					}
				}
				
				if(isset($this->se_settings['services_options'][$sc]) && intval($this->se_settings['services_options'][$sc]['status']) == 0){
					continue;
				}
				
				if(!isset($results[$sc])){
					$results[$sc] = [];
				}
				
				$delivery_days       = intval($item['delivery_days']);
				$shipping_amount     = isset($item['shipping_amount']['amount']) ? floatval($item['shipping_amount']['amount']) : 0;
				$insurance_amount    = isset($item['insurance_amount']['amount']) ? floatval($item['insurance_amount']['amount']) : 0;
				$confirmation_amount = isset($item['confirmation_amount']['amount']) ? floatval($item['confirmation_amount']['amount']) : 0;
				$other_amount        = isset($item['other_amount']['amount']) ? floatval($item['other_amount']['amount']) : 0;
				
				$total_amount = $shipping_amount + $insurance_amount + $confirmation_amount + $other_amount;
				
				$results[$sc]['error_messages'] = $item['error_messages'];
				
				$service_type   = $item['service_type'];
				if(isset($this->se_settings['services_options'][$sc]) && $this->se_settings['services_options'][$sc]['type'] != $item['service_type']){
					$service_type = $this->se_settings['services_options'][$sc]['type'];
				}
				if(isset($this->se_settings['services_options'][$sc]) && $this->se_settings['services_options'][$sc]['desc'] != ''){
					$results[$sc]['service_type'] = sprintf('<small>%s</small><br>%s', $this->se_settings['services_options'][$sc]['desc'], $service_type);
				}else{
					$results[$sc]['service_type'] = $service_type;
				}
				
				$results[$sc]['delivery_days'] = sprintf('%d business day%s', $delivery_days, ($delivery_days == 1 ? '' : 's'));
				if(isset($this->se_settings['services_options'][$sc])){
					$total_amount += $this->se_settings['services_options'][$sc]['rate'];
				}
				$results[$sc][$lbs_type]         = ($total_amount > 0) ? sprintf('$%s', $total_amount) : '-';
				$results[$sc][$lbs_type.'_sort'] = $total_amount;
				
			}
		}
		
		$results = $this->removeErrorFromResults($results);
		$results = $this->sortResults($results);
		#dd($results);
		
		return $results;
	}
	
	private function removeErrorFromResults($results){
		if(count($results) > 0){
			unset($results[""]);
		}
		
		return $results;
	}
	
	private function sortResults($results){
		foreach($results as $k => $v){
			if(!isset($v['low'])) $results[$k]['low'] = 0;
			if(!isset($v['low_sort'])) $results[$k]['low_sort'] = 0;
			if(!isset($v['high'])) $results[$k]['high'] = 0;
			if(!isset($v['high_sort'])) $results[$k]['high_sort'] = 0;
		}
		
		$low_sort  = array_column($results, 'low_sort');
		$high_sort = array_column($results, 'high_sort');
		array_multisort($low_sort, SORT_ASC, $high_sort, SORT_ASC, $results);
		
		return $results;
	}
	
	private function getServiceOptions(){
		
		$service_settings = Settings::getLike('ship_engine_');
		
		foreach($service_settings as $k => $v){
			$key = str_replace('ship_engine_', '', $k);
			
			switch($k){
				case 'ship_engine_carrier_ids':
					$this->se_settings[$key] = array_map('trim', explode(',', $v));
					break;
				case 'ship_engine_services_options':
					$options = [];
					$services_options = json_decode($v, true);
					if(!empty($services_options)){
						foreach($services_options as $k => $v){
							$options[$v['code']] = $v;
						}
					}
					$this->se_settings[$key] = $options;
					break;
				default:
					if(is_numeric($v)){
						$v = intval($v);
					}
					$this->se_settings[$key] = $v;
					break;
			}
		}
		
		#dd($this->se_settings);
	}
	
	private function addPickupData(){
		$results = [];
		
		if($this->se_settings['display_pickup_service']){
			$results['pickup'] = [
				"error_messages" => "",
				"service_type"   => "Pickup",
				"delivery_days"  => "N/A",
				"low"            => "N/A",
				"low_sort"       => 0,
				"high"           => "N/A",
				"high_sort"      => 0,
			];
		}
		
		return $results;
	}
	
}
