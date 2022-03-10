<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    if(!Schema::hasTable('design_file')){
		    Schema::create('design_file', function (Blueprint $table) {
			    $table->increments('id');
			    $table->integer('design_id')->unsigned();
			    $table->foreign('design_id')
			          ->references('id')
			          ->on('designs')
			          ->onUpdate('cascade')
			          ->onDelete('cascade');
			    $table->integer('file_id')->unsigned();
			    $table->foreign('file_id')
			          ->references('id')
			          ->on('files')
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
        Schema::dropIfExists('design_file');
    }
}
