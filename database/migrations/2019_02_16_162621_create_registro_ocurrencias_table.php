<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistroOcurrenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registro_ocurrencias', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->time('hora');
            $table->string('inicio',30);
            $table->string('telefono',100)->nullable();
            $table->string('administrado',45)->nullable();
            $table->string('ocurrencia');
            $table->integer('tipoIncidente_id')->unsigned();
            $table->integer('ubicacion_id')->unsigned();
            $table->string('referencia',45)->nullable();
            $table->text('unidadMovil')->nullable();
            $table->text('personalServicio')->nullable();
            $table->boolean('interviene');
            $table->boolean('parteServicio');
            $table->boolean('llamada');
            $table->text('detalleParteServicio')->nullable();
            $table->integer('entidad_id')->unsigned();
            $table->boolean('asume');
            $table->integer('fuenteImagen_id')->unsigned();
            $table->boolean('fin');
            $table->string('solucion', 250)->nullable();
            $table->boolean('estado')->default(1);
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('tipoIncidente_id')->references('id')->on('tipo_incidentes');
            $table->foreign('ubicacion_id')->references('id')->on('ubicacions');
            $table->foreign('fuenteImagen_id')->references('id')->on('fuente_imagens');
            $table->foreign('entidad_id')->references('id')->on('entidads');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['fecha', 'hora', 'tipoIncidente_id'], 'indice_fechaHoraIncidente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro_ocurrencias');
    }
}