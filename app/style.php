<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class style extends Model
{
    protected $fillable = ['product_name','product_shopify_id','quantity','style_info','url_svg_temp'];

    public function quote()
    {
    	return $this->belongsTo(quote::class);
    }
}
