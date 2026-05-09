<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendWhatsAppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de WhatsApp para las citas del día siguiente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $appointments = Appointment::where('date', $tomorrow)
            ->where('status', 1) // Programado
            ->with(['patient.user', 'doctor'])
            ->get();

        $this->info("Buscando citas para el día: {$tomorrow}");
        $this->info("Se encontraron " . $appointments->count() . " citas.");

        $whatsApp = new WhatsAppService();
        $count = 0;

        foreach ($appointments as $appointment) {
            if ($whatsApp->sendReminder($appointment)) {
                $count++;
                $this->line("Recordatorio enviado a: " . $appointment->patient->user->name);
            }
        }

        $this->info("Proceso completado. Recordatorios enviados: {$count}");
    }
}
