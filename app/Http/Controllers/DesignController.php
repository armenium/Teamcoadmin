<?php

namespace App\Http\Controllers;

use App\quantity;
use App\design;
use App\roster;
use App\Size;
use Illuminate\Http\Request;

class DesignController extends Controller {
	
	/**
	 * Display a listing of the resource.
	 * @return \Illuminate\Http\Response
	 */
	public function index(){
		#$designs = design::with('client')->get();
		$designs = [];
		
		return view('designs.index', ['designs' => $designs]);
	}
	
	public function parts(Request $request){
		$sort_cols = [
			0 => 'designs.id',
			1 => 'clients.name',
			2 => 'clients.company',
			3 => 'designs.created_at',
		];
		#dd($request);
		
		$query = design::query();
		
		#$query->select('roster.*, clients.name, clients.company, styles.quantity');
		$query->select('designs.*');
		$query->leftJoin('clients', 'clients.id', '=', 'designs.client_id');
		
		if(isset($request->search)){
			if(!empty($request->search['value'])){
				
				$phrase = $request->search['value'];
				$like_phrase = "%".$phrase."%";
				
				$query->where('designs.id', '=', $phrase);
				$query->orWhere('clients.name', 'like', $like_phrase);
				$query->orWhere('clients.company', 'like', $like_phrase);
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
		
		$design = [];
		
		if($data){
			foreach($data->all() as $item){
				$design[] = [
					$item->id,
					$item->client->name,
					$item->client->company,
					$item->created_at->format('M d, Y'),
					'<a href="'.route('design.show', $item->id).'" class="btn btn-primary">View Details</a>',
					'<button class="btn btn-danger btn-remove" data-reference_id="'.$item->id.'" data-toggle="modal" data-target="#myModal" data-action="'.route('design.destroy', $item->id).'" title="Delete"><i class="fa fa-trash"></i></button>',
				];
			}
		}
		
		$data = [
			'draw' => $request->draw,
			'recordsTotal' => $total_count,
			'recordsFiltered' => $total_count,
			'data' => $design,
		];
		
	
		return response()->json($data, 200);
	}
	
	/**
	 * Show the form for creating a new resource.
	 * @return \Illuminate\Http\Response
	 */
	public function create(){
		//
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request){
		//
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id){
		$design = design::findOrFail($id);
		
		$data = [
			'design' => $design,
		];
		
		return view('designs.show', $data);
	}
	
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id){
	
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
		//
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id){
		$model = design::find($id);
		$model->find($id)->delete();
		
		return redirect('design')->with('status', 'Design Destroyed');
	}
}
