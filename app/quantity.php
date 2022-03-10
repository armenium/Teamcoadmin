<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quantity extends Model
{
    protected $fillable = ['size','quantity', 'type'];

    public function roster(){
    	return $this->belongsTo(roster::class);
    }

}
