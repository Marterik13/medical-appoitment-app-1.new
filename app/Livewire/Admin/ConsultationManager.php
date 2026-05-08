<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Livewire\Component;

class ConsultationManager extends Component
{
    public Appointment $appointment;
    public $activeTab = 'consulta';
    
    // Campos de la consulta
    public $diagnosis = '';
    public $treatment = '';
    public $notes = '';
    
    // Campos de la receta
    public $medications = [];

    // Modal Historial Médico
    public $showMedicalHistoryModal = false;

    // Modal Consultas Anteriores
    public $showHistoryModal = false;
    public $pastConsultations = [];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment;
        $this->diagnosis = $appointment->diagnosis ?? '';
        $this->treatment = $appointment->treatment ?? '';
        $this->notes = $appointment->notes ?? '';
        
        $prescription = $appointment->prescription ?? [];
        if (is_string($prescription)) {
            $prescription = json_decode($prescription, true) ?? [];
        }
        $this->medications = $prescription;

        // If medications is empty, add one empty row
        if (empty($this->medications)) {
            $this->addMedication();
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addMedication()
    {
        $this->medications[] = ['name' => '', 'dose' => '', 'frequency' => ''];
    }

    public function removeMedication($index)
    {
        unset($this->medications[$index]);
        $this->medications = array_values($this->medications); // reindex
    }

    public function openMedicalHistoryModal()
    {
        $this->showMedicalHistoryModal = true;
    }

    public function closeMedicalHistoryModal()
    {
        $this->showMedicalHistoryModal = false;
    }

    public function openHistoryModal()
    {
        $this->pastConsultations = Appointment::where('patient_id', $this->appointment->patient_id)
            ->where('id', '!=', $this->appointment->id)
            ->where('status', 2) // Completado
            ->with('doctor')
            ->orderBy('date', 'desc')
            ->get();
            
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
    }

    public function save()
    {
        $this->validate([
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'notes' => 'nullable|string',
            'medications.*.name' => 'nullable|string',
            'medications.*.dose' => 'nullable|string',
            'medications.*.frequency' => 'nullable|string',
        ]);

        // Filtrar medicamentos vacíos
        $filteredMedications = array_filter($this->medications, function($med) {
            return !empty($med['name']);
        });

        $this->appointment->update([
            'diagnosis' => $this->diagnosis,
            'treatment' => $this->treatment,
            'notes' => $this->notes,
            'prescription' => array_values($filteredMedications),
            'status' => 2 // Marcar como completado
        ]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Consulta Guardada',
            'text'  => 'Los datos de la consulta se han guardado correctamente.',
        ]);

        return redirect()->route('admin.appointments.index');
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}
