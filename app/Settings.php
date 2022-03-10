<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model{
	
	protected $fillable = ['name', 'value'];
	
	public static function get($name){
		$q = self::where(['name' => $name])->select('value')->first();
		
		return ($q) ? $q->value : '';
	}
	
	public static function set($name, $value){
		self::where(['name' => $name])->update(['value' => $value]);
	}
}
