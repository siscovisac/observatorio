<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncidentesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidentes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 60)->unique();
            $table->string('descripcion')->nullable();
            $table->integer('generico_id')->unsigned();
            $table->boolean('estado')->default(1);
            $table->integer('user_id')->default(1);
            $table->timestamps();
            $table->foreign('generico_id')->references('id')->on('genericos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incidentes');
    }
}
