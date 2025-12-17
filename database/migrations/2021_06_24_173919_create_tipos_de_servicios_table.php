<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposDeServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_cc_tiposervicio', function (Blueprint $table) {
            $table->increments('id');
            $table->string('servicio',120 );
            $table->string('descripcion',120)->nullable();
            $table->tinyInteger('estado');
            $table->string('tokenx',30);
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
        Schema::dropIfExists('tb_cc_tiposervicio');
    }
}
