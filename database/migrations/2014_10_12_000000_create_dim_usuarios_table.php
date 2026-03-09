<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDimUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DIM_USUARIOS', function (Blueprint $table) {
            $table->integer('matricula', false, false)->lenght(8);
            $table->string('nome', 50);
            $table->string('sobrenome', 50);
            $table->string('cpf', 11)->unique();
            $table->date('data_nascimento');
            $table->string('nome_mae', 80);
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('senha');
            $table->rememberToken();
            $table->timestamps();

            $table->primary('matricula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DIM_USUARIOS');
    }
}