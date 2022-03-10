<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class RosterAdminMailable extends Mailable implements ShouldQueue{
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

		//Log::debug(var_export($this->data, true));
		//Log::stack(['single'])->debug(var_export($this->data, true));

		/*$template = 'email.roster.admin';
		if(isset($this->data['environment'])){
			if($this->data['environment'] == 'dev'){
				$template = 'email.roster.preview.admin';
			}
		}*/

		$message = $this->markdown('email.roster.admin')
			//->from($this->data['roster']->client->email, $this->data['roster']->client->name)
			->from(config('mail.from.address'), config('mail.from.name'))
			//->cc($this->data['roster']->client->email, $this->data['roster']->client->name)
			//->cc('armen@digidez.com', 'Armen')
			//->bcc($this->data['roster']->client->email, $this->data['roster']->client->name)
			//->replyTo('armen@mail15.com', 'Test Name')
			->replyTo($this->data['roster']->client->email, $this->data['roster']->client->name)
			->subject('Roster Form #'.$this->data['roster']->id);

		//Log::debug(var_export($message->data, true));

		if(count($this->data['roster']->files) > 0){
			foreach($this->data['roster']->files as $file){
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
