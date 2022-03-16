<?php

namespace App\Http\Controllers;


use App\design;
use App\Settings;
use App\Size;
use Illuminate\Http\Request;

class SettingsController extends Controller{

	public function __construct(){
		$this->middleware('auth');
	}

	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		#$settings = settings::with('client')->get();
		$settings = Settings::all();
		
		return view('settings.index', ['settings' => $settings]);
	}
	
	public function parts(Request $request){
		$sort_cols = [
			0 => 'id',
			1 => 'name',
			2 => 'value',
			3 => 'updated_at',
		];
		#dd($request);
		
		$query = Settings::query();
		
		$query->select('*');
		
		if(isset($request->search)){
			if(!empty($request->search['value'])){
				
				$phrase = $request->search['value'];
				$like_phrase = "%".$phrase."%";
				
				$query->where('id', '=', $phrase);
				$query->orWhere('name', 'like', $like_phrase);
				$query->orWhere('value', 'like', $like_phrase);
			}
		}
		
		if(isset($request->order)){
			foreach($request->order as $order){
				$query->orderBy($sort_cols[$order['column']], $order['dir']);
			}
		}
		
		$query->offset($request->start);
		$query->limit($request->length);
		
		#dd($query->toSql());
		
		$data = $query->get();
		$total_count = $query->getQuery()->getCountForPagination();
		
		$settings = [];
		
		if($data){
			foreach($data->all() as $item){
				$settings[] = [
					$item->id,
					$item->name,
					$item->value,
					$item->updated_at->format('M d, Y'),
					'<a href="'.route('settings.edit', $item->id).'" class="btn btn-secondary">Edit</a>',
					'<a href="'.route('settings.show', $item->id).'" class="btn btn-primary">View</a>',
					'<button class="btn btn-danger btn-remove" data-reference_id="'.$item->id.'" data-toggle="modal" data-target="#myModal" data-action="'.route('settings.destroy', $item->id).'" title="Delete"><i class="fa fa-trash"></i></button>',
				];
			}
		}
		
		$data = [
			'draw' => $request->draw,
			'recordsTotal' => $total_count,
			'recordsFiltered' => $total_count,
			'data' => $settings,
		];
		
		
		return response()->json($data, 200);
	}
	
	/**
	 * Show the form for creating a new resource.
	 * @return \Illuminate\Http\Response
	 */
	public function create(){
		return view('settings.create');
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request){
		Settings::create($request->all());
		
		return redirect('settings/create')->with('status', 'Setting Created');
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$model = Settings::findOrFail($id);
		
		$data = [
			'model' => $model,
		];
		
		return view('settings.show', $data);
	}
	
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
		$settings = Settings::findOrFail($id);
		
		return view('settings.edit', ['settings' => $settings]);
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
		Settings::find($id)->update($request->all());
		
		return redirect('settings/'.$id.'/edit')->with('status', 'Setting updated');
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		Settings::find($id)->delete();
		
		return redirect('design')->with('status', 'Setting Destroyed');
	}
}
