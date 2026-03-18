<x-admin-layout title="Usuarios" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios']
]">

    {{-- Definimos el slot 'action' aquí --}}
    <x-slot name="action">
        <x-wire-button blue href="{{ route('admin.users.create') }}">
            <i class="fa-solid fa-plus mr-2"></i>
            Nuevo
        </x-wire-button>
    </x-slot>

    <div wire:key="users-table-container">
        @livewire('admin.Datatables.user-table')
    </div>

</x-admin-layout> 