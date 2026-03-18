<x-admin-layout title="Dashboard" :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard')
    ]
]">

    <div class="p-6 bg-white rounded-lg shadow">
        <h1 class="text-2xl font-semibold text-gray-800">Bienvenido al Panel de Control</h1>
        <p class="mt-2 text-gray-600">Aquí podrás ver un resumen general de tu sistema.</p>
    </div>

</x-admin-layout>