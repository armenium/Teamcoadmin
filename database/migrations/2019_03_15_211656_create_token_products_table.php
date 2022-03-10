<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokenProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('token_products')){
            Schema::create('token_products', function (Blueprint $table) {
                $table->increments('id');
                $table->string('token')->index();
                $table->bigInteger('product_id')->unsigned();
                $table->text('data')->nullable();
                $table->text('url_svg')->nullable();
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
        Schema::dropIfExists('token_products');
    }
}
