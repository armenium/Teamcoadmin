<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('teams')){
            Schema::create('teams', function (Blueprint $table) {
                $table->increments('id');
                $table->string('size')->nullable();
                $table->string('number')->nullable();
                $table->string('name')->nullable();
                $table->integer('roster_id')->unsigned();
                $table->foreign('roster_id')
                        ->references('id')
                        ->on('rosters')
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
        Schema::dropIfExists('teams');
    }
}
