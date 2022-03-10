<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class design extends Model{
	
	protected $fillable = [
		'type_of_jerseys',
		'accessory_items',
		'quantity_required',
		'date_required',
		'description',
		'artwork'
	];
	
	public function client(){
		return $this->belongsTo(client::class);
	}
	
	public function files(){
		return $this->belongsToMany(file::class);
	}
	
}
