<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    //
    protected $fillable = ['name'];
    public function states(){
    	return $this->hasMany(state::class);
    }
}
