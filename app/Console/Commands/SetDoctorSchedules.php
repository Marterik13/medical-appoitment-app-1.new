<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SetDoctorSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'doctors:set-schedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna horarios de lunes a viernes (9 AM a 1 PM) a todos los doctores que no tengan horarios.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $doctors = Doctor::all();
        $this->info("Procesando " . $doctors->count() . " doctores...");

        foreach ($doctors as $doctor) {
            $existing = DoctorSchedule::where('doctor_id', $doctor->id)->count();
            
            if ($existing > 0) {
                $this->line("Doctor {$doctor->name} ya tiene horarios. Saltando...");
                continue;
            }

            $this->line("Asignando horarios a: {$doctor->name}");
            
            // Lunes (1) a Viernes (5)
            for ($day = 1; $day <= 5; $day++) {
                // De 09:00 a 13:00 en intervalos de 15 min
                for ($hour = 9; $hour < 13; $hour++) {
                    $minutes = ['00', '15', '30', '45'];
                    foreach ($minutes as $min) {
                        DoctorSchedule::create([
                            'doctor_id' => $doctor->id,
                            'day_of_week' => $day,
                            'start_time' => sprintf('%02d:%s:00', $hour, $min),
                            'end_time' => sprintf('%02d:%s:00', $hour, $min === '45' ? '00' : (int)$min + 15), // simplified end time
                        ]);
                    }
                }
            }
        }

        $this->info("Proceso completado.");
    }
}
