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
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 1 = Lunes, 2 = Martes, etc.
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
            
            // Un doctor no puede tener horarios duplicados superpuestos fácilmente si controlamos esto, pero a nivel bd permitiremos entradas, la lógica livewire lo limpia.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_schedules');
    }
};
