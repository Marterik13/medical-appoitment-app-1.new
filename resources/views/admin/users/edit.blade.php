<x-admin-layout title="Editar Usuario" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Editar']
]"> 

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-6">
            Editar Usuario: {{ $user->name }}
        </h2>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            {{-- Mantenemos el padding px-20 que usa el profe --}}
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                
                {{-- Muy importante: El action cambia a UPDATE y agregamos @method('PUT') --}}
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                        
                        {{-- Nombre: Cargamos el valor actual con $user->name --}}
                        <div>
                            <x-wire-input label="Nombre" name="name" 
                                :value="old('name', $user->name)" 
                                placeholder="Nombre completo" />
                        </div>

                        {{-- Email: Cargamos el valor actual con $user->email --}}
                        <div>
                            <x-wire-input label="Correo electrónico" name="email" 
                                :value="old('email', $user->email)" 
                                type="email" placeholder="ejemplo@dominio.com" />
                        </div>

                        {{-- Password: En edición el password suele ser opcional --}}
                        <div>
                            <x-wire-input name="password" label="Nueva Contraseña" 
                                type="password" placeholder="Dejar en blanco para no cambiar" />
                        </div>

                        <div>
                            <x-wire-input name="password_confirmation" label="Confirmar nueva contraseña" 
                                type="password" placeholder="Repita la contraseña" />
                        </div>

                        <div>
                            <x-wire-input label="Número de ID" name="id_number" 
                                :value="old('id_number', $user->id_number)" 
                                placeholder="Ej. 123456789" />
                        </div>

                        <div>
                            <x-wire-input label="Teléfono" name="phone" 
                                :value="old('phone', $user->phone)" 
                                placeholder="Ej. 999999999" />
                        </div>

                        <div class="md:col-span-2">
                            <x-wire-input label="Dirección" name="address" 
                                :value="old('address', $user->address)" 
                                placeholder="Ej. Calle 90 293" />
                        </div>

                        <div class="md:col-span-2">
                            <x-wire-select label="Rol" name="rol_id" placeholder="Seleccione un rol" required>
                                @foreach ($roles as $role)
                                    {{-- El profe usa la propiedad selected para marcar el rol actual --}}
                                    <x-wire-select.option label="{{ $role->name }}" value="{{ $role->id }}" 
                                        :selected="$user->hasRole($role->name)" />
                                @endforeach
                            </x-wire-select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8">
                        <x-wire-button flat label="Cancelar" href="{{ route('admin.users.index') }}" class="mr-4" />
                        <x-wire-button 
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 uppercase tracking-widest text-xs font-semibold shadow-md" 
                            label="Actualizar" 
                            type="submit" 
                        />
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</x-admin-layout>