<x-admin-layout>
    <x-slot name="header">
        {{-- 
            Nota: En la imagen del profe, el encabezado principal parece ser el del Dashboard, 
            pero mantengamos este slot por si tu layout lo requiere. 
        --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Pacientes') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- 1. BREADCRUMBS: Estilo exacto a la captura --}}
            <div class="mb-6 text-sm">
                <nav class="flex text-gray-400 font-medium">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-600">Pacientes</span>
                </nav>
            </div>

            {{-- 2. CONTENEDOR PRINCIPAL: Con el redondeado y sombra de image_2.png --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 md:p-10 border border-gray-100">
                
                {{-- 3. TÍTULO INTERNO: Negrita y limpio --}}
                <div class="mb-6">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Pacientes</h1>
                </div>

                {{-- 
                    4. COMPONENTE LIVEWIRE: 
                    Asegúrate de que el componente 'patient-table' tenga el buscador 
                    y el selector de columnas activados para que se vea igual.
                --}}
                <div class="mt-4">
                    @livewire('admin.datatables.patient-table')
                </div>

            </div>
        </div>
    </div>
</x-admin-layout>