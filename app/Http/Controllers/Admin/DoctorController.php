<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return view('admin.doctors.index'); // DoctorTable Livewire component is rendered here
    }

    public function create()
    {
        return view('admin.doctors.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors',
            'dni' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
        ]);

        Doctor::create($validated);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor creado exitosamente.');
    }

    public function edit(Doctor $doctor)
    {
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'dni' => 'required|string|max:20',
            'phone' => 'nullable|string|max:20',
            'specialty' => 'nullable|string|max:255',
        ]);

        $doctor->update($validated);

        return redirect()->route('admin.doctors.index')->with('success', 'Doctor actualizado exitosamente.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors.index')->with('success', 'Doctor eliminado exitosamente.');
    }

    public function schedules(Doctor $doctor)
    {
        return view('admin.doctors.schedules', compact('doctor'));
    }
}
