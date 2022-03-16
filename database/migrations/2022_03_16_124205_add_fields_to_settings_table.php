<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToSettingsTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::table('settings', function(Blueprint $table){
			$table->integer('active')->default(1)->unsigned()->after('value');
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('settings', function(Blueprint $table){
			$table->dropColumn('active');
		});
	}
}
