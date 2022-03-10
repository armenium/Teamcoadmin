<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if(!Schema::hasTable('designs')){
		    Schema::create('designs', function (Blueprint $table) {
			    $table->increments('id');
			    $table->longText('type_of_jerseys')->nullable();
			    $table->string('accessory_items')->nullable();
			    $table->string('quantity_required')->nullable();
			    $table->string('date_required')->nullable();
			    $table->longText('description')->nullable();
			    $table->longText('artwork')->nullable();
			    $table->integer('client_id')->unsigned();
			    $table->foreign('client_id')
			          ->references('id')
			          ->on('clients')
			          ->onUpdate('cascade')
			          ->onDelete('cascade');
			
			    $table->timestamps();
		    });
	    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designs');
    }
}
