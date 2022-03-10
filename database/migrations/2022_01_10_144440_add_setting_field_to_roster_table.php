<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSettingFieldToRosterTable extends Migration{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(){
		Schema::table('rosters', function(Blueprint $table){
			$table->text('settings')->nullable()->after('client_id');
		});
	}
	
	/**
	 * Reverse the migrations.
	 * @return void
	 */
	public function down(){
		Schema::table('rosters', function(Blueprint $table){
			$table->dropColumn('settings');
		});
	}
}
