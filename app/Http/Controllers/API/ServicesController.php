<?php

namespace App\Http\Controllers\API;

use App\Color;
use App\country;
use App\Http\Controllers\Controller;
use App\Http\SVG\arrayUtilities;
use App\Http\SVG\lotSVGHelper;
use App\Http\SVG\Svg;
use App\Product;
use App\Size;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\ShipEngine\Rates;

class ServicesController extends Controller {

	public function getColors(Request $request){
		#Log::stack(['custom'])->debug($request->productid);

		$Colors = Color::orderBy('position')->get();
		$Product = Product::where('shopify_id', $request->productid)->first();

		if($Product->colors){
			$ProductColorSets = json_decode($Product->colors, true);
			$tmp_colors = [];
			foreach($Colors as $k => $color){
				if(in_array($color->value_code, $ProductColorSets)){
					$tmp_colors[] = $color;
				}
			}
			if(!empty($tmp_colors)){
				$Colors = $tmp_colors;
			}
			unset($tmp_colors, $Product, $ProductColorSets);
		}

		return response()->json(['data' => $Colors], 200);
	}

	public function getProduct($id){
		$Product = Product::where('shopify_id', $id)->first();
		if($Product){
			$data = [
				'name'       => $Product->name,
				'url_svg'    => $Product->url_svg,
				'svg_info'   => arrayUtilities::setInfo(json_decode($Product->svg_info, true)),
				'shopify_id' => (string)$Product->shopify_id,
				'dataExtra'  => arrayUtilities::jsonData(json_decode($Product->svg_info, true))
			];

			return response()->json(['data' => $data], 200);
		}else{
			return response()->json(['data' => 'not found'], 200);
		}
	}

	public function image(Request $request){
		$product = Product::where('shopify_id', $request->productId)->first();
		$svgData = [];
		if($product->svg_info != ''){
			$svgData = json_decode($product->svg_info, true);
		}
		$color = [];
		foreach($request->customColor as $key => $value){
			$color[$key] = $value;
			$svgData     = LotSVGHelper::changeSvgClass($svgData, $key, $value);
		}
		$tempFile = 'svgTemp/'.$product->url_svg.'-'.md5(microtime(true));
		File::copy(public_path('jerseys/'.$product->url_svg.'.svg'), public_path('jerseys/'.$tempFile.'.svg'));
		# File::copy('public/jerseys/'.$product->url_svg.'.svg', 'public/jerseys/'.$tempFile.'.svg');
		# File::copy(base_path().'/public/jerseys/'.$product->url_svg.'.svg', base_path().'/public/jerseys/'.$tempFile.'.svg');
		if(SVG::updateStyleSVG($tempFile, $svgData)){
			return response()->json(['data' => $tempFile]);
		}
	}

	public function getCountryStates(){
		$Countries = Country::all();
		$data      = [];
		foreach($Countries as $key => $country){
			$data[] = [
				'name'   => ucfirst($country->name),
				'states' => $country->states,
			];
		}

		return response()->json(['data' => $data]);
	}

	public function getSizes(){
		$Sizes = Size::orderBy('weight')
		             ->where(['private' => 0])
		             ->get();

		return response()->json(['data' => $Sizes]);
	}

	public function getShippingRates(Request $request){
		$rates = new Rates();
		
		$request_data = [
			"to_country_code" => $request->country_code,
			"to_state_province" => $request->state_province,
			"to_postal_code" => $request->postal_code,
			"units" => $request->weight,
		];
	}
}

