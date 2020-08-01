<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoIncidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_incidentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre',100)->unique();
            $table->string('descripcion')->nullable();
            $table->string('ordenanza')->nullable();
            $table->integer('incidente_id')->unsigned();
            $table->boolean('estado')->default(1);
            $table->integer('user_id')->default(1);
            $table->timestamps();
            $table->foreign('incidente_id')->references('id')->on('incidentes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_incidentes');
    }
}
