<?php

namespace App\Livewire\Admin\Datatables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder; // Importante
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class UserTable extends DataTableComponent
{
    //  quitar esta propiedad o la comenta para usar el método builder
    // protected $model = User::class; 

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    /**
     
     * Define la consulta base y carga los roles para evitar errores.
     */
    public function builder(): Builder
    {
        return User::query()
            ->with('roles'); // Carga la relación de Spatie
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable(),
            Column::make("Nombre", "name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email")
                ->sortable()
                ->searchable(),
            Column::make("Número de id", "id_number")
                ->sortable()
                ->searchable(),
            Column::make("Teléfono", "phone")
                ->sortable()
                ->searchable(),
            // Aquí es donde el video suele agregar la columna de Rol
            Column::make("Rol", "roles.name") // Esto asume que el usuario tiene una relación 'roles' y quieres mostrar el nombre del primer rol
                ->label(function($row){
                    return $row->roles->first()?->name ?? 'Sin Rol';    
                }),
            
            Column::make("Acciones")
                ->label(function ($row) {
                   return view('admin.users.actions',
                    ['user' => $row]);
                })
        ];
    }
}