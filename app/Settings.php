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
	
	public static function getLike($fragment){
		$response = [];
		
		$q = self::where('name', 'like', '%'.$fragment.'%')->where('active', 1)->get();
		
		if(!is_null($q))
			foreach($q as $v)
				$response[$v->name] = $v->value;
				
		return $response;
	}
	
}
