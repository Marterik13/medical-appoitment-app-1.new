<x-admin-layout title="Editar Rol" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Roles', 'href' => route('admin.roles.index')],
    ['name' => 'Editar']
]">

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.roles.update', $role) }}" method="POST">
            @csrf
            @method('PUT') {{-- Esto es obligatorio para editar en Laravel --}}

            <div class="mb-4">
                {{-- Cargamos el nombre actual usando $role->name --}}
                <x-wire-input 
                    label="Nombre del Rol" 
                    name="name" 
                    value="{{ old('name', $role->name) }}" 
                    placeholder="Escriba el nuevo nombre"
                />
            </div>

            <div class="flex justify-end gap-2">
                <x-wire-button flat label="Cancelar" href="{{ route('admin.roles.index') }}" />
                <x-wire-button primary label="Actualizar Rol" type="submit" />
            </div>
        </form>
    </div>

</x-admin-layout>