<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\BloodType; 
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();
        return view('admin.patients.index', compact('patients'));
    }

    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();
        $patient->load('user'); // Cargamos la relación antes de retornar

        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    public function update(Request $request, Patient $patient)
    {
        // VALIDACIONES: Siguiendo el flujo del video de Victor Arana
        $data = $request->validate([
            // En un expediente médico, el tipo de sangre es obligatorio (required)
            'blood_type_id'                  => 'required|exists:blood_types,id',
            
            'allergies'                      => 'nullable|string|max:255',
            'chronic_conditions'             => 'nullable|string|max:255',
            'surgical_history'               => 'nullable|string|max:255',
            'family_history'                 => 'nullable|string|max:255',
            'observations'                   => 'nullable|string|max:255',

            // Contacto de Emergencia: Todo obligatorio para que el expediente sea válido
            'emergency_contact_name'         => 'required|string|max:25',
            
            // Usamos numeric|digits:10 para asegurar que sea un número de celular real
            'emergency_contact_phone'        => 'required|numeric|digits:10', 
            
            'emergency_contact_relationship' => 'required|string|max:25',
        ]);

        // Actualización masiva con los datos ya validados
        $patient->update($data);

        // Notificación para SweetAlert
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Registro Actualizado!',
            'text'  => 'La información médica se guardó correctamente.',
        ]);

        return redirect()->route('admin.patients.index');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        session()->flash('swal', [
            'icon'  => 'info',
            'title' => 'Eliminado',
            'text'  => 'El registro ha sido borrado.',
        ]);

        return redirect()->route('admin.patients.index');
    }
}