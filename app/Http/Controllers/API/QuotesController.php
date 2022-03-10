<?php

namespace App\Http\Controllers\API;

use App\client;
use App\Http\Controllers\Controller;
use App\Http\Shopify\Shopify;
use App\Http\SVG\arraysHelpers;
use App\Mail\AdminMailable;
use App\Mail\ClientMailable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;


class QuotesController extends Controller {
    public $shopify;
	private $logging = true;

    public function __construct(){
        $this->shopify = new Shopify;    
    }

    public function create(Request $request){


    	if($this->logging){
		    Log::stack(['custom'])->debug('// BEGIN _________________________________________');
		    Log::stack(['custom'])->debug(__CLASS__);
	    }

        $client = client::create($request->user);
        $quote = $client->quote()->create($request->quote);

	    if($this->logging){
		    Log::stack(['custom'])->debug('Teamco Web Inquiry #'.$quote->id);
	    }

        if($request->files->count() > 0){
           $data = arraysHelpers::saveFiles($request);
           $quote->files()->sync($data);  
        }
        if(isset($request->products)){
            if(count($request->products) > 0){
                $data = arraysHelpers::saveProducts($request);
                $quote->styles()->createMany($data);
            } 
        }

        $url = '/admin/customers/search.json?query='.$quote->client->email;
	    $verifyUser = (count($this->shopify->get($url)->customers) > 0) ? 'Yes' : 'No';

        $products = arraysHelpers::returnProducts($quote->styles);
	    $mail_data = [
		    'quote'           => $quote,
		    'customerShopify' => $verifyUser,
		    'products'        => $products,
		    'subject'         => 'Teamco Web Inquiry - ['.$quote->client->name.'] - #'.$quote->id,
	    ];
        //Mail::to(config('mail.from.address'))->send(new AdminMailable($mail_data));
        //Mail::to($quote->client->email)->send(new ClientMailable($mail_data));

	    // Begin Test
	    //$mailable = new ClientMailable($mail_data);
	    //$mailable->subject('Teamco Web Inquiry - ['.$quote->client->name.']');
	    //Mail::to('armen@digidez.com')->send($mailable);
	    // End Test

	    $when = Carbon::now()->addSecond(30);

	    if($this->logging){
		    Log::stack(['custom'])->debug('Adding admin mail to the task table');
	    }
	    Mail::to(config('mail.from.address'))->later($when, new AdminMailable($mail_data));

	    if($this->logging){
		    Log::stack(['custom'])->debug('Adding client mail to the task table');
	    }
	    $mailable = new ClientMailable($mail_data);
	    $mailable->subject('Teamco Web Inquiry - ['.$quote->client->name.'] - #'.$quote->id);
	    Mail::to($quote->client->email)->later($when, $mailable);
	    unset($mailable);

	    if($this->logging){
		    $jobs = $this->get_jobs_count();
		    Log::stack(['custom'])->debug('Tasks in table: count('.$jobs['count'].'), ids('.$jobs['ids'].')');
		    Log::stack(['custom'])->debug('// END');
	    }

        return response()->json(['data'=>$quote, 'message' => 'success' ],200);
    }

	private function get_jobs_count(){
		$results = DB::table('jobs')->pluck('id');

		$count = $results->count();
		$ids = [];
		foreach($results as $result){
			$ids[] = $result;
		}

		return ['ids' => implode(', ', $ids), 'count' => $count];
	}

}
