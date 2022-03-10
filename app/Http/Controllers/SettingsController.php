<?php

namespace App\Http\Controllers;


class SettingsController extends Controller{

	public $shopify;

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		#$settings = settings::with('client')->get();
		$settings = [];
		
		return view('settings.index', ['settings' => $settings]);
	}


}
