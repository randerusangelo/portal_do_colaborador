<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimUsuarioLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DIM_USUARIOS_LOGS', function (Blueprint $table) {
            $table->integer('matricula', false, false)->lenght(8);
            $table->string('email', 100);
            $table->datetime('data_hora');
            $table->string('ip', 15)->nullable();

            $table->primary([
                'matricula',
                'email',
                'data_hora'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DIM_USUARIOS_LOGS');
    }
}
