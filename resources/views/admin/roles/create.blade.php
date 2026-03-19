<x-admin-layout title="Crear Rol" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Roles', 'href' => route('admin.roles.index')],
    ['name' => 'Nuevo']
]"> 

    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.roles.store') }}" method="POST">
            @csrf
            
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-white border border-gray-200 shadow-sm rounded-lg">
                    <div class="flex items-center mb-2">
                        <h3 class="text-xl font-bold text-red-600">
                            ¡Ups! Algo salió mal.
                        </h3>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li class="font-medium">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6">
                {{-- QUITAMOS EL MANEJO AUTOMÁTICO DE WIREUI PARA QUE NO SE DUPLIQUE --}}
                <x-wire-input 
                    label="Nombre" 
                    placeholder="Escriba el nombre del rol" 
                    name="name" 
                    :value="old('name')"
                    {{-- Esta línea es clave para que WireUI no oculte el error de la lista --}}
                    :shadowless="true" 
                />
                <p class="mt-2 text-sm text-gray-500">
                    Define los permisos y el acceso del usuario.
                </p>
            </div>
              
            <div class="flex justify-end gap-2 border-t pt-4">
                <x-wire-button flat label="Cancelar" href="{{ route('admin.roles.index') }}" />
                <x-wire-button primary label="Guardar" type="submit" />
            </div>
        </form>
    </div>
</x-admin-layout>