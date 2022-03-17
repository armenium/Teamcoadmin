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

                $phrase      = $request->search['value'];
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

        $data        = $query->get();
        $total_count = $query->getQuery()->getCountForPagination();

        $settings = [];

        if($data){
            foreach($data->all() as $item){
                $settings[] = [
                    $item->id,
                    $item->name,
                    $item->value,
                    $item->updated_at->format('M d, Y'),
                    '<a href="'.route('settings.edit', $item->id).'" class="btn btn-secondary"><i class="fa fa-edit"></i></a>',
                    '<a href="'.route('settings.show', $item->id).'" class="btn btn-primary"><i class="fa fa-eye"></i></a>',
                    '<button class="btn btn-danger btn-remove" data-reference_id="'.$item->id.'" data-toggle="modal" data-target="#myModal" data-action="'.route('settings.destroy', $item->id).'" title="Delete"><i class="fa fa-trash"></i></button>',
                ];
            }
        }

        $data = [
            'draw'            => $request->draw,
            'recordsTotal'    => $total_count,
            'recordsFiltered' => $total_count,
            'data'            => $settings,
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

        return redirect('settings')->with('status', 'Setting Created');
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
	
	    switch($model->name){
		    case "ship_engine_services_options":
			    $model->value = $this->arrayToHtmlTable(json_decode($model->value, true));
			    #$model->value = '<pre>'.print_r(json_decode($model->value, true), true).'</pre>';
			    break;
	    }
	
	
	    $data = ['model' => $model];

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
        $model = Settings::findOrFail($id);
        $form = 'settings.edit';
        $json_data = [];

        switch($model->name){
            case "ship_engine_services_options":
                $form = 'settings.edit-seso';
                $json_data = json_decode($model->value, true);
                break;
        }

        return view($form, ['settings' => $model, 'json_data' => $json_data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
	    $request_data = $request->all();
    	$values = $request_data['value'];
    	#dd($request_data);
    	
    	foreach($values as $k => $v){
		    if(is_null($v['rate'])){
			    $request_data['value'][$k]['rate'] = 0;
		    }else{
			    $request_data['value'][$k]['rate'] = floatval($request_data['value'][$k]['rate']);
		    }
		    if(!isset($v['status'])){
			    $request_data['value'][$k]['status'] = 0;
		    }else{
			    $request_data['value'][$k]['status'] = intval($request_data['value'][$k]['status']);
		    }
	    }
	
	    #dd($request_data);
	    $request_data['value'] = json_encode($request_data['value']);
    	
    	
        Settings::find($id)->update($request_data);

        return redirect('settings/'.$id.'/')->with('status', 'Setting updated');
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
    
    private function arrayToHtmlTable($data){
    	$html = [];
    	$html[] = '<table class="table table-striped">';
    	foreach($data as $k => $v){
    		$html[] = sprintf('<tr><td>%s</td></tr>', implode('</td><td>', $v));
	    }
    	$html[] = '</table>';
    	
    	return implode(PHP_EOL, $html);
    }
    
}
