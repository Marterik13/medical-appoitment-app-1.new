<x-admin-layout title="Crear Rol" :breadcrumbs="[
    [
        'name' => 'Dashboard', 
        'href' => route('admin.dashboard')
    ],
    [
        'name' => 'Roles',
        'href' => route('admin.roles.index') {{-- Corregido: de 'route' a 'href' --}}
    ],
    [
        'name' => 'Nuevo',
    ]
]"> 

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                {{-- Usamos x-wire-input por el prefijo de tu archivo wireui.php --}}
                <x-wire-input 
                    label="Nombre del Rol" 
                    placeholder="Escriba el nombre del rol (ej. Recepcionista)" 
                    name="name" 
                    value="{{ old('name') }}" 
                />
            </div>

            <div class="flex justify-end gap-2">
                <x-wire-button flat label="Cancelar" href="{{ route('admin.roles.index') }}" />
                <x-wire-button primary label="Guardar" type="submit" />
            </div>
        </form>
    </div>

</x-admin-layout>