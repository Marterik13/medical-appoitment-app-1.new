<?php

namespace App\Livewire\Admin\Datatables;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;

class PatientTable extends DataTableComponent
{
    protected $model = Patient::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setTheme('tailwind');

        // 1. Estilo de la TABLA (Arreglo)
        $this->setTableAttributes([
            'class' => 'min-w-full divide-y divide-gray-100 border border-gray-100 rounded-lg shadow-sm',
        ]);
        
        // 2. Estilo de las FILAS (Cebreado gris/blanco)
        $this->setTrAttributes(function($row, $index) {
            return [
                'class' => $index % 2 === 0 ? 'bg-gray-50 hover:bg-gray-100' : 'bg-white hover:bg-gray-50',
            ];
        });

        // 3. Estilo de ENCABEZADOS (Función)
        $this->setThAttributes(function() {
            return [
                'class' => 'px-6 py-3 bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider',
            ];
        });

        // 4. CONFIGURACIÓN DE VISIBILIDAD (Nombres actualizados)
        $this->setSearchStatus(true);
        $this->setColumnSelectStatus(true);
        $this->setPaginationStatus(true);
        
        // CORRECCIÓN AQUÍ: Agregamos 'Visibility' al nombre del método
        $this->setPerPageVisibilityStatus(true); 
    }

    public function builder(): Builder
    {
        return Patient::query()->with('user');
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")->sortable(),
            Column::make("NOMBRE", "user.name")->sortable()->searchable(),
            Column::make("EMAIL", "user.email")->sortable()->searchable(),
            Column::make("NUMERO DE ID", "user.id_number")->sortable()->searchable(),
            Column::make("TELEFONO", "user.phone")->sortable(),
            Column::make("ACCIONES")->label(fn($row) => view('admin.patients.actions', ['patient' => $row])),
        ];
    }
}