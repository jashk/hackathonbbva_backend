<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('muid', 100)->unique()->comment('Id unico del vendedor');
            $table->string('firts_name', 100)->comment('Nombre del vendedor');
            $table->string('last_name', 100)->comment('Appellido(os)del vendedor');
            $table->string('phone', 20)->comment('Tel del vendedor');
            $table->string('business_social_name', 100)->comment('Nombre de negocio');
            $table->string('business_social_rfc', 15)->comment('RFC');
            $table->string('business_social_address', 300)->comment('Direccion del Negocio');
            $table->decimal('business_social_address_lat', 10, 8)->nullable()->comment('Direccion del Negocio');
            $table->decimal('business_social_address_lng', 11, 8)->nullable()->comment('Direccion del Negocio');
            $table->string('business_start', 20)->comment('Horario de Inicio del Negocio');
            $table->string('business_end', 20)->comment('Horario de Cierre del Negocio');
            $table->smallInteger('approved')->comment('Indica si la solicitud fue aprobada');
            $table->smallInteger('status')->comment('Estatus interno');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchant');
    }
}
