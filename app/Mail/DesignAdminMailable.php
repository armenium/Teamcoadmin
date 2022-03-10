<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class DesignAdminMailable extends Mailable implements ShouldQueue{
	//use Queueable, SerializesModels;
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $data;

	/**
	 * Create a new message instance.
	 * @return void
	 */
	public function __construct($data){
		$this->data = $data;
	}

	/**
	 * Build the message.
	 * @return $this
	 */
	public function build(){


		$message = $this->markdown('email.design.admin')
			->from(config('mail.from.address'), config('mail.from.name'))
			#->cc('armen@digidez.com', 'Armen')
			->replyTo($this->data['design']->client->email, $this->data['design']->client->name)
			->subject('Custom Design Form #D'.$this->data['design']->id);

		//Log::debug(var_export($message->data, true));

		if(count($this->data['design']->files) > 0){
			foreach($this->data['design']->files as $file){
				$message->attach(public_path($file->url), [
					'as' => $file->name
				]);
			}
		}

		return $message;
	}

	public function failed(){
		// Вызывается при ошибке в задаче...
		Log::stack(['custom'])->debug('Sending mail failed');
	}
}
