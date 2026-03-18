<x-admin-layout title="Roles" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Roles']
]">

    {{-- Definimos el slot 'action' aquí --}}
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.roles.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    <div wire:key="roles-table-container">
        @livewire('admin.Datatables.role-table')
    </div>

</x-admin-layout> 