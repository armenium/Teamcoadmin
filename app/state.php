<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class state extends Model
{
    //
    protected $fillable = ['name'];

    public function country()
    {
    	return $this->belongsTo(country::class);
    }
}
