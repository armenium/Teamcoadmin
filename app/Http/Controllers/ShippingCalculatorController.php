<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

class ShippingCalculatorController extends Controller{

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$custom_fields = Settings::get('ship_engine_jersey_type_options');
		
		if(!is_null($custom_fields)){
			$custom_fields = json_decode($custom_fields, true);
		}else{
			$custom_fields = [];
		}
		
		return view('shipping.index', ['custom_fields' => $custom_fields]);
	}
	
}
