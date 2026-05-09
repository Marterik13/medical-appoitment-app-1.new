<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Models\DoctorSchedule;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        // Asignar horarios por defecto (Lunes a Viernes de 9 AM a 1 PM)
        for ($day = 1; $day <= 5; $day++) {
            for ($hour = 9; $hour < 13; $hour++) {
                $minutes = ['00', '15', '30', '45'];
                foreach ($minutes as $min) {
                    DoctorSchedule::create([
                        'doctor_id' => $doctor->id,
                        'day_of_week' => $day,
                        'start_time' => sprintf('%02d:%s:00', $hour, $min),
                        'end_time' => sprintf('%02d:%s:00', $hour, $min === '45' ? '00' : (int)$min + 15),
                    ]);
                }
            }
        }
    }

    /**
     * Handle the Doctor "updated" event.
     */
    public function updated(Doctor $doctor): void
    {
        //
    }

    /**
     * Handle the Doctor "deleted" event.
     */
    public function deleted(Doctor $doctor): void
    {
        // Limpiar horarios al borrar el doctor
        $doctor->schedules()->delete();
    }

    /**
     * Handle the Doctor "restored" event.
     */
    public function restored(Doctor $doctor): void
    {
        //
    }

    /**
     * Handle the Doctor "force deleted" event.
     */
    public function forceDeleted(Doctor $doctor): void
    {
        //
    }
}
