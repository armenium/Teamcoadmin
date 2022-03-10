<?php

namespace App\Http\Shopify;

use App;
use Illuminate\Support\Facades\Log;

class ShipEngine {
	private $api_version = 'v1';
	private $base_url = 'https://api.shipengine.com';
	
	public function connect(){
		$this->api_version = config('app.shipengine')['SHIPENGINE_API_VERSION'];
		
		$hendler = App::make('ShipEngineAPI');
		
		$hendler->setup([
			'Host: api.shipengine.com',
			'API-Key: '.config('app.shopify')['SHIPENGINE_API_KEY'],
			'Content-Type: application/json'
		]);
		
		return $hendler;
	}
	
	private function create_url($url){
		$url_fragment = $this->base_url.'/'.$this->api_version.'/'.$url;
		Log::stack(['laravel'])->debug('API URL: '.$url_fragment);
		
		return $url_fragment;
	}
	
	public function get($url){
		$get = $this->connect()->call([
			'METHOD' => 'GET',
			'URL'    => $this->create_url($url)
		]);
		
		return $get;
	}
	
	public function post($url, $data){
		$post = $this->connect()->call([
			'METHOD' => 'POST',
			'URL'    => $this->create_url($url),
			'DATA'   => $data
		]);
		
		return $post;
	}
	
	public function update($url, $data){
		$update = $this->connect()->call([
			'METHOD' => 'PUT',
			'URL'    => $this->create_url($url),
			'DATA'   => $data
		]);
		
		return $update;
	}
	
	public function updateDefaultAddress($url){
		$update = $this->connect()->call([
			'METHOD' => 'PUT',
			'URL'    => $this->create_url($url),
		]);
		
		return $update;
	}
	
	public function delete($url){
		$delete = $this->connect()->call([
			'METHOD' => 'DELETE',
			'URL'    => $this->create_url($url)
		]);
		
		return $delete;
	}
}



