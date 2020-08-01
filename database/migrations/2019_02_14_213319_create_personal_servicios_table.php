<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonalServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_servicios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apellidos',45);
            $table->string('nombres',45);
            $table->string('dni',8)->nullable();
            $table->date('fechaNacimiento')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono',9)->nullable();
            $table->string('correo')->nullable();
            $table->date('fechaIngreso')->nullable();
            $table->char('grupo',1);
            $table->integer('cargo_id')->unsigned();
            $table->date('fechaCese')->nullable();
            $table->string('observacion')->nullable();
            $table->boolean('estado')->default(1);
            $table->integer('user_id')->default(1);
            $table->timestamps();
            $table->foreign('cargo_id')->references('id')->on('cargos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personal_servicios');
    }
}
