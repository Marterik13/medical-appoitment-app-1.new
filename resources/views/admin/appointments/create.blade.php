<x-admin-layout title="Nuevo" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Citas', 'href' => route('admin.appointments.index')],
    ['name' => 'Nuevo']
]">

    @livewire('admin.appointment-creator')

</x-admin-layout>
