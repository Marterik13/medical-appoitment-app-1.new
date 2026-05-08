<?php

namespace App\Livewire\Admin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Doctor;

class DoctorTable extends DataTableComponent
{
    protected $model = Doctor::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->searchable()
                ->sortable(),
            Column::make("Email", "email")
                ->searchable()
                ->sortable(),
            Column::make("DNI", "dni")
                ->searchable()
                ->sortable(),
            Column::make("Telefono", "phone")
                ->searchable()
                ->sortable(),
            Column::make("Especialidad", "specialty")
                ->searchable()
                ->sortable(),
            Column::make('Acciones', 'id')
                ->format(function ($value, $row, Column $column) {
                    $editUrl = route('admin.doctors.edit', $value);
                    $scheduleUrl = route('admin.doctors.schedules', $value);

                    return '
                        <div class="flex gap-2 justify-center">
                            <a href="'.$editUrl.'" class="w-8 h-8 inline-flex items-center justify-center text-white bg-blue-500 hover:bg-blue-600 rounded shadow-sm focus:outline-none transition-colors">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="'.$scheduleUrl.'" class="w-8 h-8 inline-flex items-center justify-center text-white bg-green-500 hover:bg-green-600 rounded shadow-sm focus:outline-none transition-colors" title="Gestor de horarios">
                                <i class="fa-regular fa-clock"></i>
                            </a>
                        </div>
                    ';
                })->html()
        ];
    }
}
