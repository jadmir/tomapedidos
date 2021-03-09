<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGxPedidodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gx_pedidod', function (Blueprint $table) {
            $table->id('idpedidod');
            //$table->string('gx_producto_idproducto');
            $table->unsignedBigInteger('gx_producto_idproducto');
            $table->decimal('n_cantidad');
            $table->string('c_subtotal', 100);
            $table->string('c_producto', 250);
            $table->string('c_comentario', 250);
            //$table->string('gx_pedidoc_idpedido');
            $table->unsignedBigInteger('gx_pedidoc_idpedido');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('gx_producto_idproducto')->references('idproducto')->on('gx_productos');

            $table->foreign('gx_pedidoc_idpedido')->references('idpedido')->on('gx_pedidoc');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gx_pedidod');
    }
}
