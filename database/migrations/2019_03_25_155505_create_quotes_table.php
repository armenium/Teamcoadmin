<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('quotes')){
            Schema::create('quotes', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('description')->nullable();
                $table->string('date_required')->nullable();
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
        Schema::dropIfExists('quotes');
    }
}
