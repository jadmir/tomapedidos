<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGxPedidocTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gx_pedidoc', function (Blueprint $table) {
            $table->id('idpedido');
            $table->datetime('d_fecha_hora_pedido_inicio');
            $table->datetime('d_fecha_hora_pedido_final');
            $table->string('c_contacto', 250);
            $table->string('c_telefono_contacto', 12);
            $table->string('c_direccion', 250);
            $table->string('c_referencia', 250);
            $table->string('c_donde', 250);
            $table->decimal('n_delivery', 10, 2);
            $table->decimal('n_total_pedido', 10, 2);
            $table->enum('c_medio_pago', ['EFECTIVO', 'TARJETA', 'YAPE']);
            $table->string('c_medio_pago_comentario', 250);
            $table->datetime('d_tiempo_entrega');
            $table->string('gx_usuario_dni_pedido', 10);
            //$table->unsignedInteger('gx_usuario_dni_pedido');
            $table->string('gx_usuario_dni_despacho', 10);
            //$table->unsignedBigInteger('gx_usuario_dni_despacho');
            $table->string('gx_cliente_dni', 10);
            //$table->unsignedBigInteger('gx_cliente_dni');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('gx_usuario_dni_pedido')->references('c_dni')->on('gx_usuarios');

            $table->foreign('gx_usuario_dni_despacho')->references('c_dni')->on('gx_usuarios');

            $table->foreign('gx_cliente_dni')->references('c_dni')->on('gx_clientes');



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gx_pedidoc');
    }
}
