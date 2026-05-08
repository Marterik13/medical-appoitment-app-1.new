<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient.user', 'doctor'])->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::all();
        
        return view('admin.appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'reason'     => 'nullable|string|max:1000',
        ]);
        
        // Calculate duration if needed, or default it
        $start = \Carbon\Carbon::parse($data['start_time']);
        $end = \Carbon\Carbon::parse($data['end_time']);
        $data['duration'] = $start->diffInMinutes($end);
        $data['status'] = 1; // 1 = Programado

        Appointment::create($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita Creada',
            'text'  => 'La cita se registró correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::with('user')->get();
        $doctors = Doctor::all();
        
        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:doctors,id',
            'date'       => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'status'     => 'required|integer',
            'reason'     => 'nullable|string|max:1000',
        ]);
        
        $start = \Carbon\Carbon::parse($data['start_time']);
        $end = \Carbon\Carbon::parse($data['end_time']);
        $data['duration'] = $start->diffInMinutes($end);

        $appointment->update($data);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Cita Actualizada',
            'text'  => 'La cita se actualizó correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Show the consultation interface for a specific appointment.
     */
    public function consultation(Appointment $appointment)
    {
        // View that contains the Livewire ConsultationManager component
        $appointment->load(['patient.user', 'doctor']);
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
