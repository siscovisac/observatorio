<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetallePersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_personals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registroOcurrencia_id')->unsigned();
            $table->integer('personal_id')->unsigned();
            $table->boolean('estado')->default(1);
            $table->foreign('registroOcurrencia_id')->references('id')->on('registro_ocurrencias');
            $table->foreign('personal_id')->references('id')->on('personal_servicios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_personals');
    }
}
