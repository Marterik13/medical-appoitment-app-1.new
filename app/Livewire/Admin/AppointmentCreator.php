<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use Livewire\Component;
use Carbon\Carbon;

class AppointmentCreator extends Component
{
    // Search fields
    public $searchDate;
    public $searchTime; // Optional: specific time filter like '08:00 - 09:00'
    public $searchSpecialty;

    // Available data
    public $specialties = [];
    public $doctorsWithSlots = [];

    // Selected data for appointment
    public $selectedDoctorId = null;
    public $selectedTime = null; // Format 'H:i'
    
    // Patient data
    public $patients = [];
    public $patient_id = '';
    public $reason = '';

    public function mount()
    {
        $this->searchDate = date('Y-m-d');
        // Get unique specialties
        $this->specialties = Doctor::whereNotNull('specialty')->distinct()->pluck('specialty')->toArray();
        $this->patients = Patient::with('user')->get();
        
        $this->searchAvailability();
    }

    public function updatedSearchDate()
    {
        $this->searchAvailability();
        $this->clearSelection();
    }

    public function updatedSearchSpecialty()
    {
        $this->searchAvailability();
        $this->clearSelection();
    }
    
    public function updatedSearchTime()
    {
        $this->searchAvailability();
    }

    public function clearSelection()
    {
        $this->selectedDoctorId = null;
        $this->selectedTime = null;
    }

    public function selectSlot($doctorId, $time)
    {
        $this->selectedDoctorId = $doctorId;
        $this->selectedTime = $time;
    }

    public function searchAvailability()
    {
        if (!$this->searchDate) return;

        $date = Carbon::parse($this->searchDate);
        // Carbon dayOfWeek returns 0 (Sunday) to 6 (Saturday).
        // Our system uses 1 (Monday) to 6 (Saturday). Adjusting:
        $dayOfWeek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek; // Si es domingo, en nuestra BD no hay domingo, o si lo hay es 7.

        $query = Doctor::query();
        
        if ($this->searchSpecialty) {
            $query->where('specialty', $this->searchSpecialty);
        }

        $searchDate = $this->searchDate;
        $doctors = $query->with(['appointments' => function($q) use ($searchDate) {
            $q->where('date', $searchDate);
        }])->get();

        $this->doctorsWithSlots = [];

        foreach ($doctors as $doc) {
            // Get schedules for this day
            $schedules = DoctorSchedule::where('doctor_id', $doc->id)
                ->where('day_of_week', $dayOfWeek)
                ->get();

            if ($schedules->isEmpty()) continue;

            $bookedTimes = $doc->appointments->pluck('start_time')->map(function($time) {
                return Carbon::parse($time)->format('H:i:s');
            })->toArray();

            $slots = [];
            foreach ($schedules as $schedule) {
                // Generar los slots de 15 minutos dentro de cada bloque de horario del doctor
                // En nuestra BD guardamos slots de 15 minutos exactos si usamos el gestor, pero vamos a agruparlos
                $start = Carbon::parse($schedule->start_time);
                
                // Si el gestor guarda bloques individuales de 15 min, simplemente los listamos
                $timeString = $start->format('H:i');
                $fullTimeString = $start->format('H:i:s');
                
                // Filter by selected time range if provided
                if ($this->searchTime) {
                    list($rangeStart, $rangeEnd) = explode(' - ', $this->searchTime);
                    $rangeStartCarbon = Carbon::parse($rangeStart);
                    $rangeEndCarbon = Carbon::parse($rangeEnd);
                    if ($start->lt($rangeStartCarbon) || $start->gte($rangeEndCarbon)) {
                        continue;
                    }
                }

                if (!in_array($fullTimeString, $bookedTimes)) {
                    $slots[] = $timeString;
                }
            }

            // Remove duplicate slots if any due to data issues, and sort
            $slots = array_unique($slots);
            sort($slots);

            if (!empty($slots)) {
                $this->doctorsWithSlots[] = [
                    'doctor' => $doc,
                    'slots' => $slots
                ];
            }
        }
    }

    public function confirmAppointment()
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'selectedDoctorId' => 'required|exists:doctors,id',
            'searchDate' => 'required|date|after_or_equal:today',
            'selectedTime' => 'required|date_format:H:i',
        ]);

        $start = Carbon::parse($this->selectedTime);
        $end = $start->copy()->addMinutes(15);

        Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->searchDate,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'duration' => 15,
            'reason' => $this->reason,
            'status' => 1 // Programado
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita Confirmada',
            'text'  => 'La cita se ha programado exitosamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function getSelectedDoctorProperty()
    {
        if ($this->selectedDoctorId) {
            return Doctor::find($this->selectedDoctorId);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.admin.appointment-creator');
    }
}
