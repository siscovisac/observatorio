<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntidadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entidads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45)->unique();
            $table->string('descripcion')->nullable();
            $table->string('direccion')->nullable();
            $table->enum('origen', ['INTERNO', 'EXTERNO'])->default('INTERNO');
            $table->boolean('estado')->default(1);
            $table->integer('user_id')->default(1);
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
        Schema::dropIfExists('entidads');
    }
}
