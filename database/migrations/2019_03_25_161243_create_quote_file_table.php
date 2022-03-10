<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuoteFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('file_quote')){
            Schema::create('file_quote', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('quote_id')->unsigned();
                $table->foreign('quote_id')
                    ->references('id')
                    ->on('quotes')
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
        Schema::dropIfExists('file_quote');
    }
}
