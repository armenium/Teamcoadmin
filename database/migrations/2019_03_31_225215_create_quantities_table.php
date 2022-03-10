<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuantitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('quantities')){
            Schema::create('quantities', function (Blueprint $table) {
                $table->increments('id');
                $table->string('size')->nullable();
                $table->string('quantity')->nullable();
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
        Schema::dropIfExists('quantities');
    }
}
