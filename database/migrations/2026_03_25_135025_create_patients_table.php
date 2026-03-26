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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();   
            
            // 1. Relación con Usuarios 
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // 2. Relación con Tipos de Sangre (
            $table->foreignId('blood_type_id')
                  ->nullable()
                  ->constrained('blood_types')
                  ->onDelete('set null');

            // 3. Campos de información médica
            $table->string('allergies')->nullable();
            $table->string('chronic_conditions')->nullable();
            $table->string('surgical_history')->nullable();
            $table->string('family_history')->nullable();
            $table->text('observations')->nullable(); 
                
            // 4. Contacto de Emergencia
            $table->string('emergency_contact_name')->nullable(); 
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};