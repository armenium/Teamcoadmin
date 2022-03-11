<?php

namespace App\Http\ShipEngine;

use App;
use Illuminate\Support\Facades\Log;

class ShipEngine {
	
	private $api_version = 'v1';
	private $base_url = 'https://api.shipengine.com';
	private $headers = [];
	
	public function __construct(){
		$config = config('app.shipengine');
		
		$this->api_version = $config['SHIPENGINE_API_VERSION'];
		
		$this->headers = [
			'Host: api.shipengine.com',
			'API-Key: '.$config['SHIPENGINE_API_KEY'],
			'Content-Type: application/json'
		];
	}
	
	private function create_url($url){
		$url_fragment = $this->base_url.'/'.$this->api_version.'/'.$url;
		Log::stack(['laravel'])->debug('API URL: '.$url_fragment);
		
		return $url_fragment;
	}
	
	public function call($params){
		$curl = curl_init();
		
		curl_setopt_array($curl, [
			CURLOPT_URL => $params['URL'],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $params['METHOD'],
			CURLOPT_POSTFIELDS => $params['DATA'],
			CURLOPT_HTTPHEADER => $this->headers,
		]);
		
		$response = curl_exec($curl);
		
		curl_close($curl);
		
		return $response;
	}
	
	public function get($url, $data){
		$get = $this->call([
			'METHOD' => 'GET',
			'URL'    => $this->create_url($url),
			'DATA'   => $data
		]);
		
		return json_decode($get, true);
	}
	
	public function post($url, $data){
		$post = $this->call([
			'METHOD' => 'POST',
			'URL'    => $this->create_url($url),
			'DATA'   => $data
		]);
		
		return json_decode($post, true);
	}
	
}



