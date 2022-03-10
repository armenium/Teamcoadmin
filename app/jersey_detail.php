<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class jersey_detail extends Model
{
    //
    protected $fillable= ['style_code','colors'];

    public function roster(){
    	return $this->belongsTo(roster::class);
    }
}
