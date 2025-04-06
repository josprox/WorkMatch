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
        // 2. Tabla usuarios (vinculado por Token con API externa)
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id')->primary(); // id auto incrementable, llave primaria
            $table->string('token_user', 128)->unique()->comment('Token único de usuario para autenticación o identificación'); // token como PK
            $table->text('especialidades')->nullable();
            $table->text('curriculum')->nullable();
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
