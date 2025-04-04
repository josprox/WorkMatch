<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 4. Tabla candidaturas
        Schema::create('candidaturas', function (Blueprint $table) {
            $table->id();
            $table->string('token_user', 128); // referencia a usuarios.token_user
            $table->unsignedBigInteger('empresa_id'); // quien ofrece
            $table->unsignedBigInteger('vacante_id')->nullable(); // opcional vinculación a vacante específica

            $table->foreign('token_user')->references('token_user')->on('usuarios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('vacante_id')->references('id')->on('vacantes')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
