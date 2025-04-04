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
        // 3. Tabla vacantes
        Schema::create('vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100)->nullable();
            $table->text('descripcion')->nullable();
            $table->decimal('sueldo', 10, 2)->nullable(); // formato sueldo decimal con 2 decimales
            $table->string('modalidad', 100)->nullable(); // remoto, híbrido, presencial...
            $table->unsignedBigInteger('empresa_id');

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
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
