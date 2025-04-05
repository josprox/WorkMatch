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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id(); // id BIGINT auto_increment
            $table->string('nombre', 100)->comment('Nombre de la empresa');
            $table->string('correo', 100)->unique()->comment('Correo electrónico del cliente');
            $table->string('contra', 255)->comment('Contraseña encriptada del cliente');
            $table->string('ubicacion', 100)->nullable()->comment('Ubicación de la empresa');
            $table->string('telefono', 20)->nullable()->comment('Teléfono de la empresa');
            $table->timestamps(); // created_at, updated_at
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
