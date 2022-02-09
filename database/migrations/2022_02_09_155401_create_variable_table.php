<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variable', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subcategoria_id');
            $table->foreign('subcategoria_id')->references('id')->on('subcategoria');
            $table->string('nombre');            
            $table->string('descripcion')->unique();           
            $table->string('unidad');           
            $table->tinyInteger('estado')->default(1);
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
        Schema::dropIfExists('variable');
    }
}
