<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductShopify extends Model {
	
	protected $table = 'products_shopify';
	protected $fillable = ['shopify_id', 'title'];
	
}

