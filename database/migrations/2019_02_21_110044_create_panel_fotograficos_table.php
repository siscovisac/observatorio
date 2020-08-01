<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePanelFotograficosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_fotograficos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('registroOcurrencia_id')->unsigned();
            $table->string('fotoOriginal');
            $table->string('leyenda');
            $table->timestamps();
            $table->foreign('registroOcurrencia_id')->references('id')->on('registro_ocurrencias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('panel_fotograficos');
    }
}
