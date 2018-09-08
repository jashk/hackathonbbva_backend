<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->comment('Nombre del producto');
            $table->string('imagen', 100)->comment('Imagen del producto');
            $table->decimal('price',20,2)->comment("Precio de la aplicacion");
            $table->integer('merchant_id');
            $table->timestamps();

            $table->foreign('merchant_id')->references('id')->on('merchant');
        });

        Schema::table('producto',function (Blueprint $table){

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
