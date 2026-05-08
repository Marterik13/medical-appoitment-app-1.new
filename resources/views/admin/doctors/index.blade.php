<x-admin-layout title="Doctores" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Doctores']
]">

    <div class="bg-white p-4 shadow-sm border border-gray-100 sm:rounded-lg dark:bg-gray-800 dark:border-gray-700">
        @livewire('admin.doctor-table')
    </div>

</x-admin-layout>
