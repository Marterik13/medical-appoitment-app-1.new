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
        // Cambiamos 'blody_types' por 'blood_types'
        Schema::create('blood_types', function (Blueprint $table) {
            $table->id();

            $table->string('name')
                  ->unique(); // Ejemplo: A+, O-, AB+, etc.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // También corregimos aquí para que el rollback funcione
        Schema::dropIfExists('blood_types');
    }
};