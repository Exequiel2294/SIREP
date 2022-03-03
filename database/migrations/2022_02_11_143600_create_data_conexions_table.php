<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataConexionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_conexions', function (Blueprint $table) {
            $table->id();
            $table->date('Fecha');
            $table->string('Descripcion',400);
            $table->string('Atributo',400);
            $table->string('Unidad de medida',100)->nullable();
            $table->decimal('Valor',18,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data_conexions');
    }
}
