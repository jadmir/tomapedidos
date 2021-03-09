<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGxClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gx_clientes', function (Blueprint $table) {
            $table->string('c_dni', 10)->primary();
            $table->string('c_nombres', 200);
            $table->string('c_direccion', 200);
            $table->string('c_referencia', 200);
            $table->string('c_telefono_contacto', 12);
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
        Schema::dropIfExists('gx_clientes');
    }
}
