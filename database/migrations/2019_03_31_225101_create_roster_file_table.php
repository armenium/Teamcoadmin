<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRosterFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('file_roster')){
            Schema::create('file_roster', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('roster_id')->unsigned();
                $table->foreign('roster_id')
                        ->references('id')
                        ->on('rosters')
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
        Schema::dropIfExists('file_roster');
    }
}
