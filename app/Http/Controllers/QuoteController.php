<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\SVG\arraysHelpers;
use App\quote;
use App\client;
use App\Http\Shopify\Shopify;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Mail;
//use App\Mail\AdminMailable;
//use App\Mail\ClientMailable;

class QuoteController extends Controller
{
    public $shopify;
    public function __construct()
    {
        $this->middleware('auth');
        $this->shopify = new Shopify;    
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
	public function index(){
		$quotes = [];
		#$quotes = quote::all();
		
		return view('quotes.index', ['quotes' => $quotes]);
	}

	public function test(){
		$quotes = [];
		#$quotes = quote::all();
		
		return view('quotes.test', ['quotes' => $quotes]);
	}

	public function parts(Request $request){
		$sort_cols = [
			0 => 'quotes.id',
			1 => 'clients.name',
			2 => 'clients.company',
			3 => 'quotes.created_at',
			#4 => 'quantity',
			#5 => 'id',
			#6 => 'id',
		];
		#dd($request);
		#$quotes = quote::all()->sortByDesc('id')->slice($request->start, $request->length);
		#$query = DB::table('quotes');
		$query = quote::query();
		
		#$query->select('quotes.*, clients.name, clients.company, styles.quantity');
		$query->select('quotes.*');
		$query->leftJoin('clients', 'clients.id', '=', 'quotes.client_id');

		if(isset($request->search)){
			if(!empty($request->search['value'])){
				
				$phrase = $request->search['value'];
				$like_phrase = "%".$phrase."%";
				
				$query->where('quotes.id', '=', $phrase);
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
		
		$quotes = [];
		
		if($data){
			foreach($data->all() as $quote){
				$quotes[] = [
					$quote->id,
					$quote->client->name,
					$quote->client->company,
					$quote->created_at->format('M d, Y'),
					$quote->styles->sum('quantity'),
					'<a href="'.route('quotes.show', $quote->id).'" class="btn btn-primary">View Details</a>',
					'<button class="btn btn-danger btn-remove" data-reference_id="'.$quote->id.'" data-toggle="modal" data-target="#myModal" data-action="'.route('quotes.destroy', $quote->id).'" title="Delete"><i class="fa fa-trash"></i></button>',
				];
			}
		}
		
		$data = [
			'draw' => $request->draw,
			'recordsTotal' => $total_count,
			'recordsFiltered' => $total_count,
			'data' => $quotes,
		];
		
		#dd($quotes);
		
		return response()->json($data, 200);
	}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $quote = quote::findOrFail($id);

        $verifyUser = $this->checkUserShopify($quote->client->email);
        $products = arraysHelpers::returnProducts($quote->styles);

        $data = [
            'quote'=>$quote,
            'customerShopify'=>$verifyUser,
            'products'=>$products
        ];
       // Mail::to($quote->client->email)->send(new AdminMailable($data));
       // Mail::to($quote->client->email)->send(new ClientMailable($data));

        return view('quotes.show',$data);
    }
    public function checkUserShopify($email)
    {
        $url = '/admin/customers/search.json?query='.$email;
        if(count($this->shopify->get($url)->customers) > 0){
            if($this->shopify->get($url)->customers[0]->tags == 'dealer')
            {
                return 'Yes';
            }else{
		return 'No';
	    }
        }else{
            return 'No';
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
	    $quote = quote::find($id);
	    $quote->find($id)->delete();
	    return redirect('quotes')->with('status', 'Quote Destroyed');
    }
}
