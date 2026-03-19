@props([
    'title' => config('app.name', 'Laravel'),
    'breadcrumbs' => [], 
]) 
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <script src="https://kit.fontawesome.com/b2bb8bbf2e.js" crossorigin="anonymous"></script>

        <wireui:scripts />
        @wireUiScripts
    </head>
    <body class="font-sans antialiased bg-gray-100">

    @include('layouts.includes.admin.navigation')
    @include('layouts.includes.admin.sidebar')

    <div class="p-4 sm:ml-64 mt-14">
       <div class="mt-14 flex justify-between items-center w-full">
            @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs])
            
            @isset($action)
                <div>
                    {{ $action }}
                </div>
            @endisset
       </div>
       
       <main class="mt-4">
           {{ $slot }}
       </main>
    </div>

    @stack('modals')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Alerta de éxito (cuando ya se eliminó o creó algo) --}}
    @if(session('swal'))
        <script>
            Swal.fire(@json(session('swal')));
        </script>
    @endif

    {{-- SCRIPT DE CONFIRMACIÓN (NUEVO) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Escuchamos el evento click en el documento
            document.addEventListener('submit', function(e) {
                // Si el formulario tiene la clase 'delete-form'
                if (e.target.classList.contains('delete-form')) {
                    e.preventDefault(); // Detenemos el envío
                    
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esto!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Si confirma, enviamos el formulario físicamente
                            e.target.submit();
                        }
                    });
                }
            });
        });
    </script>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.2.1/dist/flowbite.min.js"></script>
    </body>
</html>