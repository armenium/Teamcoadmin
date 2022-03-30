<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class roster extends Model{
	
	protected $fillable = ['reference', 'comments', 'number_color', 'inside_color', 'outside_color', 'accessory_items', 'settings', 'shipping_method'];
	
	public function client(){
		return $this->belongsTo(client::class);
	}
	
	public function files(){
		return $this->belongsToMany(file::class);
	}
	
	public function jersey(){
		return $this->hasOne(jersey_detail::class);
	}
	
	public function quantities(){
		return $this->hasMany(quantity::class);
	}
	
	public function teams(){
		return $this->hasMany(team::class);
	}
	
	public function quantitySumByType($type){
		$quantity = $this->hasMany(quantity::class)->where(['type' => $type]);
		
		return $quantity->sum('quantity');
	}
	
}
