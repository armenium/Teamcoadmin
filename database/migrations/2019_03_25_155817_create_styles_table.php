<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStylesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('styles')){
            Schema::create('styles', function (Blueprint $table) {
                $table->increments('id');
                $table->string('product_name')->nullable();
                $table->bigInteger('product_shopify_id')->nullable();
                $table->integer('quantity')->nullable();
                $table->longText('style_info')->nullable();
                $table->longText('url_svg_temp')->nullable();
                $table->integer('quote_id')->unsigned();
                $table->foreign('quote_id')
                    ->references('id')
                    ->on('quotes')
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
        Schema::dropIfExists('styles');
    }
}
