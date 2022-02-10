<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('data_id');
            $table->foreign('data_id')->references('id')->on('data');
            $table->timestamp('fecha', 0);
            $table->string('transcaccion');  
            $table->decimal('valorviejo', 12, 2);   
            $table->decimal('valornuevo', 12, 2); 
            $table->string('usuario'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial');
    }
}
