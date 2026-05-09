<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmationMail;
use App\Mail\DailyReportMail;
use App\Services\WhatsAppService;

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
            'reason' => 'required|string|min:5|max:255',
        ]);

        $start = Carbon::parse($this->selectedTime);
        $end = $start->copy()->addMinutes(15);

        $appointment = Appointment::create([
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->selectedDoctorId,
            'date' => $this->searchDate,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'duration' => 15,
            'reason' => $this->reason,
            'status' => 1 // Programado
        ]);

        // --- AUTOMATIZACIONES INMEDIATAS ---
        try {
            // 1. Enviar Email con PDF adjunto al paciente
            Mail::to($appointment->patient->user->email)
                ->send(new AppointmentConfirmationMail($appointment));

            // Pequeña pausa para no saturar el límite de Mailtrap Free (5 emails por segundo)
            sleep(1);

            // 2. Enviar Email con PDF adjunto al doctor
            Mail::to($appointment->doctor->email)
                ->send(new AppointmentConfirmationMail($appointment));

            // 3. Enviar mensaje de WhatsApp al paciente (Confirmación con link de PDF y Recordatorio)
            $whatsApp = new WhatsAppService();
            $whatsApp->sendConfirmation($appointment);
            
            // Pequeña pausa para CallMeBot
            sleep(1);
            $whatsApp->sendReminder($appointment);

            // 4. PRUEBA DE REPORTE DIARIO (Se envía cada vez por petición del usuario para pruebas)
            $this->triggerDailyReports();
        } catch (\Exception $e) {
            \Log::error("Error en automatizaciones inmediatas: " . $e->getMessage());
        }
        // ------------------------------------

        $this->dispatch('appointment-confirmed', url: route('admin.appointments.pdf', $appointment));

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita Confirmada',
            'text'  => 'La cita se ha programado exitosamente. Abriendo comprobante...',
        ]);
    }

    protected function triggerDailyReports()
    {
        $today = Carbon::today()->toDateString();
        
        // 1. Reporte para Administrador (Todas las citas del día)
        $allAppointments = Appointment::where('date', $today)
            ->with(['patient.user', 'doctor'])
            ->orderBy('start_time')
            ->get();

        if ($allAppointments->isNotEmpty()) {
            $admins = User::role('Admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new DailyReportMail($allAppointments, 'Administrador'));
            }
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
        }
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
