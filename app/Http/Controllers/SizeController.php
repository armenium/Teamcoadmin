<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Size;

class SizeController extends Controller{
	public function __construct(){
		$this->middleware('auth');
	}
	
	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$Sizes = Size::all();
		
		return view('sizes.index', ['Sizes' => $Sizes]);
	}
	
	/**
	 * Show the form for creating a new resource.
	 * @return \Illuminate\Http\Response
	 */
	public function create(){
		//
		return view('sizes.create');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request){
		if(!isset($request['private']))
			$request['private'] = 0;

		$size = Size::create($request->all());
		
		return redirect('sizes/create')->with('status', 'Size Created');
		
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		$size = Size::findOrFail($id);
		
		return view('sizes.edit', ['size' => $size]);
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id){
		#dd($request->toArray());
		
		if(!isset($request['private']))
			$request['private'] = 0;
		
		$size = Size::find($id)->update($request->all());
		
		return redirect('sizes/'.$id.'/edit')->with('status', 'Size updated');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		$size = size::find($id)->delete();
		
		return redirect('sizes')->with('status', 'Size Destroyed');
	}
}
