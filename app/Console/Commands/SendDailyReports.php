<?php

namespace App\Console\Commands;

use App\Mail\DailyReportMail;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-daily-reports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera y envía los reportes diarios de citas por correo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today()->toDateString();
        
        // 1. Reporte para el Administrador (Todas las citas del día)
        $allAppointments = Appointment::where('date', $today)
            ->with(['patient.user', 'doctor'])
            ->orderBy('start_time')
            ->get();

        if ($allAppointments->isNotEmpty()) {
            // Buscamos al admin (asumiendo que tiene el rol 'Admin')
            $admins = User::role('Admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new DailyReportMail($allAppointments, 'Administrador'));
            }
            $this->info("Reporte general enviado a " . $admins->count() . " administradores.");
        }

        // 2. Reporte para cada Doctor (Sus citas del día)
        $doctors = Doctor::whereHas('appointments', function($q) use ($today) {
            $q->where('date', $today);
        })->get();

        foreach ($doctors as $doctor) {
            $doctorAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $today)
                ->with(['patient.user'])
                ->orderBy('start_time')
                ->get();

            Mail::to($doctor->email)->send(new DailyReportMail($doctorAppointments, 'Doctor'));
            $this->line("Reporte enviado al Dr(a). {$doctor->name}");
        }

        $this->info("Proceso de reportes finalizado.");
    }
}
