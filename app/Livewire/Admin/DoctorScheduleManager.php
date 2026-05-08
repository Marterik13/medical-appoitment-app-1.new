<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Livewire\Component;

class DoctorScheduleManager extends Component
{
    public Doctor $doctor;
    
    // Array para almacenar los horarios seleccionados en formato "dia_hora" ej. "1_08:00:00"
    public $selectedSlots = [];
    
    public $days = [
        1 => 'LUNES',
        2 => 'MARTES',
        3 => 'MIÉRCOLES',
        4 => 'JUEVES',
        5 => 'VIERNES',
        6 => 'SÁBADO'
    ];

    public $hours = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
    public $intervals = ['00', '15', '30', '45'];

    public function mount(Doctor $doctor)
    {
        $this->doctor = $doctor;
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $schedules = DoctorSchedule::where('doctor_id', $this->doctor->id)->get();
        foreach ($schedules as $schedule) {
            $day = $schedule->day_of_week;
            $start = \Carbon\Carbon::parse($schedule->start_time)->format('H:i');
            $this->selectedSlots[] = $day . '_' . $start;
        }
    }

    public function save()
    {
        // Limpiar horarios anteriores
        DoctorSchedule::where('doctor_id', $this->doctor->id)->delete();

        // Guardar nuevos horarios
        $newSchedules = [];
        foreach ($this->selectedSlots as $slot) {
            if (empty($slot)) continue;
            
            list($day, $time) = explode('_', $slot);
            
            // Calculamos el end_time sumando 15 minutos (por defecto de este sistema)
            $startTime = \Carbon\Carbon::parse($time);
            $endTime = $startTime->copy()->addMinutes(15);
            
            $newSchedules[] = [
                'doctor_id' => $this->doctor->id,
                'day_of_week' => $day,
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DoctorSchedule::insert($newSchedules);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Horario Guardado',
            'text'  => 'Los horarios se han guardado correctamente.',
        ]);
        
        return redirect()->route('admin.doctors.index');
    }

    public function toggleHourAll($day, $hour)
    {
        // This is a helper function to toggle all 4 slots in a given hour for a given day
        $prefix = $day . '_' . $hour;
        $slotsToToggle = [
            $prefix . ':00',
            $prefix . ':15',
            $prefix . ':30',
            $prefix . ':45'
        ];
        
        $allChecked = true;
        foreach ($slotsToToggle as $s) {
            if (!in_array($s, $this->selectedSlots)) {
                $allChecked = false;
                break;
            }
        }
        
        if ($allChecked) {
            $this->selectedSlots = array_diff($this->selectedSlots, $slotsToToggle);
        } else {
            foreach ($slotsToToggle as $s) {
                if (!in_array($s, $this->selectedSlots)) {
                    $this->selectedSlots[] = $s;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.doctor-schedule-manager');
    }
}
