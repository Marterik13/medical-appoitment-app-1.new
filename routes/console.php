<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// --- PROGRAMACIÓN DE TAREAS (Task Scheduling) ---

// 1. Enviar recordatorios de WhatsApp exactamente un día antes
Schedule::command('appointments:send-reminders')->daily();

// 2. Reporte automático enviado por correo todos los días a las 8:00 AM
Schedule::command('appointments:send-daily-reports')->dailyAt('08:00');
