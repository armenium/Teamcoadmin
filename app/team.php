<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class team extends Model
{
    protected $fillable = ['size','number','name','note','shortsize', 'rowcolor'];

    public function roster()
    {
    	$this->belongsTo(roster::class);
    }
}
