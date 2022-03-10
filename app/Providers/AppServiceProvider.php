<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Log\Logger;

class AppServiceProvider extends ServiceProvider {
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(){

	    Queue::before(function (JobProcessing $event) {
		    // $event->connectionName
		    // $event->job
		    // $event->job->payload()
		    #$event->job->delete();
		    #Log::stack(['custom'])->debug(__FUNCTION__.' :: Event name: '.$event->connectionName);
	    });

	    Queue::after(function (JobProcessed $event) {
		    // $event->connectionName
		    // $event->job
		    // $event->job->payload()
		    #$event->job->delete();
		    #Log::stack(['custom'])->debug(__FUNCTION__.' :: Event name: '.$event->connectionName);
	    });

	    Queue::failing(function (JobFailed $event) {
		    // $event->connectionName
		    // $event->job
		    // $event->exception
		    #$event->job->delete();
		    #Log::stack(['custom'])->debug(__FUNCTION__.' :: Event name: '.$event->connectionName);
	    });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(){
        //
    }

}
