<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quote extends Model
{
    protected $fillable = [
        'description','date_required'
    ];
    public function styles(){
    	return $this->hasMany(style::class);
    }
    public function client(){
    	return $this->belongsTo(client::class);
    }
    public function files(){
    	return $this->belongsToMany(file::class);
    }
}
