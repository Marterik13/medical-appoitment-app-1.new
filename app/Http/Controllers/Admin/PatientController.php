<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\BloodType; 
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Muestra el listado de pacientes.
     */
    public function index()
    {
        $patients = Patient::with('user')->get();
        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        // Lógica de creación...
    }

    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Muestra el formulario para editar la información médica.
     */
    public function edit(Patient $patient)
    {
        // CAMBIO AQUÍ: Usamos $bloodTypes (camelCase) para que tu Blade lo reconozca
        $bloodTypes = BloodType::all();
        
        $patient->load('user');

        // Pasamos la variable corregida a la vista
        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Guarda los cambios de la información médica.
     */
    public function update(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'blood_type_id'                  => 'nullable|exists:blood_types,id',
            'allergies'                      => 'nullable|string|max:255',
            'chronic_conditions'             => 'nullable|string',
            'surgical_history'               => 'nullable|string',
            'family_history'                 => 'nullable|string',
            'observations'                   => 'nullable|string',
            'emergency_contact_name'         => 'required|string|max:255',
            'emergency_contact_phone'        => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',
        ]);

        $patient->update($data);

        // Notificación para SweetAlert
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Perfil actualizado!',
            'text'  => 'La información médica se guardó correctamente.',
        ]);

        return redirect()->route('admin.patients.index');
    }

    /**
     * Eliminar el registro de paciente.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Paciente eliminado',
            'text'  => 'El registro médico ha sido borrado.',
        ]);

        return redirect()->route('admin.patients.index');
    }
}