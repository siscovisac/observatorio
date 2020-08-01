<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleMovilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_movils', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registroOcurrencia_id')->unsigned();
            $table->integer('movil_id')->unsigned();
            $table->boolean('estado')->default(1);
            $table->foreign('registroOcurrencia_id')->references('id')->on('registro_ocurrencias');
            $table->foreign('movil_id')->references('id')->on('unidad_movils');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_movils');
    }
}
