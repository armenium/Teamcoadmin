<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class file extends Model{
	
	protected $fillable = ['name', 'url', 'description'];
	
	public function quotes(){
		return $this->belongsToMany(quote::class);
	}
	
	public function rosters(){
		return $this->belongsToMany(roster::class);
	}
	
	public function designs(){
		return $this->belongsToMany(design::class);
	}
}
