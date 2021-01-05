<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguidoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguidores', function (Blueprint $table) {
            $table->unsignedBigInteger('id_us');
            $table->unsignedBigInteger('id_li');
            $table->boolean('creador_se')->default(false);
            $table->boolean('add_se')->default(false);
            $table->boolean('vall_se')->default(false);
            $table->boolean('contra_se')->default(false);
            $table->foreign('id_us')->references('id')->on('users');
            $table->foreign('id_li')->references('id_li')->on('listas');
            $table->primary(array('id_us', 'id_li'));
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
        Schema::dropIfExists('seguidores');
    }
}
