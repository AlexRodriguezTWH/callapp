<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFallasTable extends Migration
{

    public function up()
    {
        Schema::create('tb_cc_fallas', function (Blueprint $table) {
          $table->increments('id');
          $table->tinyInteger('tipo')->default(0);
          $table->string('descripcion',250 );
          $table->tinyInteger('estado')->default(0);
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
        Schema::dropIfExists('tb_cc_fallas');
    }
}
