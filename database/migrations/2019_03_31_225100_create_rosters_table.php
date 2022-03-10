<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRostersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('rosters')){
            Schema::create('rosters', function (Blueprint $table) {
                $table->increments('id');
                $table->string('reference')->nullable();
                $table->longText('comments')->nullable();
                $table->integer('number_color')->nullable();
                $table->string('inside_color')->nullable();
                $table->string('outside_color')->nullable();
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
        Schema::dropIfExists('rosters');
    }
}
