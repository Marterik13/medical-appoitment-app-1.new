<x-admin-layout title="Crear Usuario" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Crear']
]"> 

    {{-- El profe usa un contenedor con padding lateral --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Título "Crear" alineado a la izquierda, fuera del card --}}
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-6">
            Crear
        </h2>

        {{-- El card blanco de Jetstream usa shadow-xl y rounded-lg --}}
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Lista de errores--}}
                    @if ($errors->any())
                        <div class="mb-6">
                            <div class="font-bold text-red-600 text-lg">¡Ups! Algo salió mal.</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                     
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <x-wire-input label="Nombre" name="name" :value="old('name')" placeholder="Nombre completo" />
                        </div>

                        <div>
                            <x-wire-input label="Correo electrónico" name="email" :value="old('email')" type="email" placeholder="ejemplo@dominio.com" />
                        </div>

                        <div>
                            <x-wire-input name="password" label="Contraseña" type="password" placeholder="Mínimo 8 caracteres" required />
                        </div>

                        <div>
                            <x-wire-input name="password_confirmation" label="Confirmar contraseña" type="password" placeholder="Repita la contraseña" required />
                        </div>

                        <div>
                            <x-wire-input label="Número de ID" name="id_number" :value="old('id_number')" placeholder="Ej. 123456789" />
                        </div>

                        <div>
                            <x-wire-input label="Teléfono" name="phone" :value="old('phone')" placeholder="Ej. 999999999" />
                        </div>

                        {{-- Dirección y Rol ocupan todo el ancho (col-span-2) --}}
                        <div class="md:col-span-2">
                            <x-wire-input label="Dirección" name="address" :value="old('address')" placeholder="Ej. Calle 90 293" />
                        </div>

                        <div class="md:col-span-2">
                            <x-wire-select label="Rol" name="rol_id" placeholder="Seleccione un rol" required>
                                @foreach ($roles as $role)
                                    <x-wire-select.option label="{{ $role->name }}" value="{{ $role->id }}" />
                                @endforeach
                            </x-wire-select>
                            <p class="mt-2 text-sm text-gray-500">Define los permisos y accesos del usuario</p>
                        </div>
                    </div>

                    {{-- Botón --}}
                    <div class="flex items-center justify-end mt-8">
                        <x-wire-button 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 uppercase tracking-widest text-xs font-semibold shadow-md" 
                            label="Guardar" 
                            type="submit" 
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>