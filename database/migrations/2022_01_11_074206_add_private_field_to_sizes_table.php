<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrivateFieldToSizesTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::table('sizes', function(Blueprint $table){
			$table->integer('private')->unsigned()->default(0)->after('color');
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('sizes', function(Blueprint $table){
			$table->dropColumn('private');
		});
	}
}
