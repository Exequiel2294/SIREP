<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data', function (Blueprint $table) {            
            $table->bigIncrements('id');
            $table->unsignedBigInteger('variable_id');
            $table->foreign('variable_id')->references('id')->on('variable');
            $table->date('fecha');
            $table->decimal('valor', 12, 2);       
            $table->tinyInteger('estado')->default(1);              
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('data');
    }
}
