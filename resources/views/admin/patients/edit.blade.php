<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BREADCRUMBS: Se mantienen visibles --}}
            <nav class="flex text-sm text-gray-400 mb-4">
                <ol class="inline-flex items-center space-x-1">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-600">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('admin.patients.index') }}" class="hover:text-gray-600">Pacientes</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-600 font-medium">Editar</li>
                </ol>
            </nav>

            {{-- TÍTULO: Se mantiene visible --}}
            <h1 class="text-2xl font-bold text-gray-800 mb-8">Editar</h1>

            {{-- CONTENEDOR: Ahora estará vacío o con un mensaje temporal --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-8 border border-gray-100">
                
                <p class="text-gray-500 italic text-center py-10">
                
                </p>

                
            </div>
        </div>
    </div>
</x-admin-layout>