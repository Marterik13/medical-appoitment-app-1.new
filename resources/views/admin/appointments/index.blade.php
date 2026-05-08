<x-admin-layout title="Citas" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas']
]">

    <x-slot name="action">
        <a href="{{ route('admin.appointments.create') }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none dark:focus:ring-blue-800">
            <i class="fa-solid fa-plus mr-2"></i> Nuevo
        </a>
    </x-slot>

    <div class="bg-white p-4 shadow-sm border border-gray-100 sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
        @livewire('admin.appointment-table')
    </div>

</x-admin-layout>
