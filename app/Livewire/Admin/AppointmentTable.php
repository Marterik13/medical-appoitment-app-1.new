<?php

namespace App\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;

class AppointmentTable extends DataTableComponent
{
    protected $model = Appointment::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setDefaultSort('date', 'desc');
    }

    public function builder(): Builder
    {
        return Appointment::query()
            ->with(['patient.user', 'doctor']);
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("Paciente", "patient.user.name")
                ->searchable()
                ->sortable(),
            Column::make("Doctor", "doctor.name")
                ->searchable()
                ->sortable(),
            Column::make("Fecha", "date")
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('d/m/Y'))
                ->sortable(),
            Column::make("Hora", "start_time")
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i'))
                ->sortable(),
            Column::make("Hora fin", "end_time")
                ->format(fn($value) => \Carbon\Carbon::parse($value)->format('H:i'))
                ->sortable(),
            Column::make("Estado", "status")
                ->format(function($value) {
                    if($value == 1) return '<span class="text-gray-900 font-medium">Programado</span>';
                    if($value == 2) return '<span class="text-gray-900 font-medium">Completado</span>';
                    if($value == 3) return '<span class="text-gray-900 font-medium">Cancelado</span>';
                    return '<span class="text-gray-900 font-medium">Desconocido</span>';
                })
                ->html()
                ->sortable(),
            Column::make('Acciones', 'id')
                ->format(function ($value, $row, Column $column) {
                    $editUrl = route('admin.appointments.edit', $value);
                    $consultationUrl = route('admin.appointments.consultation', $value);
                    $pdfUrl = route('admin.appointments.pdf', $value);

                    return '
                        <div class="flex gap-2">
                            <a href="'.$editUrl.'" class="text-white bg-blue-500 hover:bg-blue-600 rounded px-2 py-1 text-sm inline-flex items-center justify-center focus:outline-none">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="'.$consultationUrl.'" class="text-white bg-green-500 hover:bg-green-600 rounded px-2 py-1 text-sm inline-flex items-center justify-center focus:outline-none" title="Atender Cita">
                                <i class="fa-solid fa-file-medical"></i>
                            </a>
                            <a href="'.$pdfUrl.'" target="_blank" class="text-white bg-red-500 hover:bg-red-600 rounded px-2 py-1 text-sm inline-flex items-center justify-center focus:outline-none" title="Ver Comprobante PDF">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                        </div>
                    ';
                })->html()
        ];
    }
}
