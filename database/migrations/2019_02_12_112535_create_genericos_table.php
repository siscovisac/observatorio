<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenericosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genericos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45)->unique();
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('genericos');
    }
}
