<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJerseyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('jersey_details')){
            Schema::create('jersey_details', function (Blueprint $table) {
                $table->increments('id');
                $table->string('style_code')->nullable();
                $table->longText('colors')->nullable();
                $table->timestamps();
                $table->integer('roster_id')->unsigned();
                $table->foreign('roster_id')
                    ->references('id')
                    ->on('rosters')
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
        Schema::dropIfExists('jersey_details');
    }
}
