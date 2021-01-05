<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_pr');
            $table->unsignedBigInteger('id_us');
            $table->unsignedBigInteger('id_li');
            $table->string('detalle_pr');
            $table->enum('estado_pr',['Por comprar','Comprado','Enviado en USA','Cancelado','Entregado en USA','Envio al Ecuador','Recibido en Ecuador','Envio Local','Entregado'])->default('Por comprar');
            $table->string('rastreo_pr')->nullable();
            $table->string('erastreo_pr')->nullable();
            $table->boolean('fragil_pr')->default(false);
            $table->string('url_pr')->nullable();
            $table->float('pvp_pr')->nullable();
            $table->float('cant_pr')->default(1);
            $table->float('libras_pr')->nullable();
            $table->float('librasR_pr')->nullable();
            $table->foreign('id_li')->references('id_li')->on('listas');
            $table->foreign('id_us')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
