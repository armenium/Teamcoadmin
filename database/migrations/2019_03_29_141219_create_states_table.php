<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('states')){
            Schema::create('states', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('state_code');
                $table->integer('country_id')->unsigned();
                $table->timestamps();
                $table->foreign('country_id')
                    ->references('id')
                    ->on('countries')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('states');
    }
}
