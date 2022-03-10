<?php

namespace App\Http\Controllers;

use App\Color;
use App\Http\Requests\StoreColor;
use Illuminate\Http\Request;
use App\Product;

class ColorController extends Controller{

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		$Colors = Color::orderBy('position', 'ASC')->get();

		return view('colors.index', ['Colors' => $Colors]);
	}

	/**
	 * Show the form for creating a new resource.
	 * @return \Illuminate\Http\Response
	 */
	public function create(){
		return view('colors.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreColor $request){
		$lastColor = Color::orderBy('position')->get()->last()->position + 1;
		$request->merge(['position' => $lastColor]);
		$color = Color::create($request->all());

		$this->updateProductsColors(false);

		return redirect('color/create')->with('status', 'Colour Created');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		$color = Color::findOrFail($id);

		return view('colors.edit', ['color' => $color]);
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
		$color = Color::find($id)->update($request->all());

		$this->updateProductsColors(false);

		return redirect('color/'.$id.'/edit')->with('status', 'Colour updated');
	}

	/**
	 * Update the positions in the table.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function orderColors(Request $request){

		foreach($request->position as $key => $value){
			Color::where('id', $key)->update(['position' => $value]);
		}

		$this->updateProductsColors(true);

		return response('Update Succesfully', 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		$color = Color::find($id)->delete();

		$this->updateProductsColors(true);

		return redirect('color')->with('status', 'Color Destroyed');
	}

	public function updateProductsColors($for_all_products = false){
		$Colors = Color::orderBy('position', 'ASC')->get();

		$_colors = [];

		foreach($Colors as $color)
			$_colors[] = $color->value_code;

		Product::where('color_autoupdate', 1)->update(['colors' => json_encode($_colors)]);

		if($for_all_products){
			$Products = Product::all()->where('color_autoupdate', 0);

			if(!empty($Products)){
				foreach($Products as $product){
					$product_color = json_decode($product->colors, true);
					$new_product_color = [];
					foreach($product_color as $k => $color){
						if(in_array($color, $_colors)){
							$new_product_color[] = $color;
						}
					}

					Product::where('id', $product->id)->update(['colors' => json_encode($new_product_color)]);
				}
			}
		}
	}


}
