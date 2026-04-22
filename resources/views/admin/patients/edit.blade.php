<x-admin-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BREADCRUMBS --}}
            <nav class="flex text-sm text-gray-400 mb-4">
                <ol class="inline-flex items-center space-x-1">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-gray-600">Dashboard</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li><a href="{{ route('admin.patients.index') }}" class="hover:text-gray-600">Pacientes</a></li>
                    <li><span class="mx-2">/</span></li>
                    <li class="text-gray-600 font-medium">Editar</li>
                </ol>
            </nav>

            <h1 class="text-2xl font-bold text-gray-800 mb-8">Editar</h1>

            <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Encabezado con foto y acciones --}}
                <x-wire-card class="mb-6">
                    <div class="lg:flex lg:justify-between lg:items-center">
                        <div class="flex items-center">
                            <img src="{{$patient->user->profile_photo_url}}" alt="{{ $patient->user->name }}" class="h-20 w-20 rounded-full object-cover shadow-md">
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">{{$patient->user->name }}</p>
                                <p class="text-sm text-gray-500">Expediente de Paciente</p>
                            </div>
                        </div>
                        <div class="flex space-x-3 mt-6 lg:mt-0">
                            <x-wire-button outline gray href="{{ route('admin.patients.index') }}">Volver</x-wire-button>
                            <x-wire-button type="submit" primary>
                                <i class="fa-solid fa-check mr-2"></i> Guardar cambios
                            </x-wire-button>
                        </div>
                    </div>
                </x-wire-card>

                {{-- Tabs de navegación --}}
                <x-wire-card>
                    <div x-data="{ tab: 'antecedentes' }">
                        
                        {{-- Menu de pestañas --}}
                        <div class="border-b border-gray-200">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center text-gray-500">
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'datos-personales'" 
                                       :class="tab === 'datos-personales' ? 'text-blue-600 border-blue-600 active' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                                       class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                                        <i class="fa-solid fa-user me-2"></i> Datos personales
                                    </a>
                                </li>
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'antecedentes'" 
                                       :class="tab === 'antecedentes' ? 'text-blue-600 border-blue-600 active' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                                       class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                                        <i class="fa-solid fa-file-lines me-2"></i> Antecedentes
                                    </a>
                                </li>
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'informacion-general'" 
                                       :class="tab === 'informacion-general' ? 'text-blue-600 border-blue-600 active' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                                       class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                                        <i class="fa-solid fa-info me-2"></i> Información general
                                    </a>
                                </li>
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'contacto-emergencia'" 
                                       :class="tab === 'contacto-emergencia' ? 'text-blue-600 border-blue-600 active' : 'border-transparent hover:text-blue-600 hover:border-gray-300'"
                                       class="inline-flex items-center justify-center p-4 border-b-2 rounded-t-lg group transition-colors duration-200">
                                        <i class="fa-solid fa-heart me-2"></i> Contacto de emergencia
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- Contenido de los Tabs --}}
                        <div class="px-4 mt-6">
                            
                            {{-- Contenido: Datos personales --}}
                            <div x-show="tab === 'datos-personales'" x-transition>
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-user-gear text-blue-500 text-xl"></i>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-bold text-blue-800">Edición de cuenta</h3>
                                            <p class="text-xs text-blue-600">La información de acceso debe gestionarse desde la cuenta asociada.</p>
                                        </div>
                                    </div>
                                    <x-wire-button primary sm label="Editar usuario" target="_blank" href="{{ route('admin.users.edit', $patient->user) }}" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <p><span class="text-gray-500 font-semibold">Teléfono:</span> <span class="ml-1 text-gray-900">{{ $patient->user->phone }}</span></p>
                                    <p><span class="text-gray-500 font-semibold">Email:</span> <span class="ml-1 text-gray-900">{{ $patient->user->email }}</span></p>
                                    <p class="md:col-span-2"><span class="text-gray-500 font-semibold">Dirección:</span> <span class="ml-1 text-gray-900">{{ $patient->user->address }}</span></p>
                                </div>
                            </div>

                            {{-- Contenido: Antecedentes (CORREGIDO A 2 COLUMNAS) --}}
                            <div x-show="tab === 'antecedentes'" x-transition>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    
                                    <x-wire-textarea label="Alergias conocidas" name="allergies" placeholder="Medicamentos, alimentos, etc.">
                                        {{ old('allergies', $patient->allergies) }}
                                    </x-wire-textarea>

                                    <x-wire-textarea label="Antecedentes Patológicos" name="chronic_conditions" placeholder="Enfermedades crónicas...">
                                        {{ old('chronic_conditions', $patient->chronic_conditions) }}
                                    </x-wire-textarea>

                                    <x-wire-textarea label="Antecedentes Quirúrgicos" name="surgical_history" placeholder="Cirugías previas y fechas...">
                                        {{ old('surgical_history', $patient->surgical_history) }}
                                    </x-wire-textarea>

                                   
                                   
                                        <x-wire-textarea label="Antecedentes Familiares" name="family_history" placeholder="Diabetes, cáncer o hipertensión en la familia...">
                                            {{ old('family_history', $patient->family_history) }}
                                        </x-wire-textarea>
                                    
                                    
                                </div>
                            </div>

                            {{-- Contenido de Tab: Información general --}}
<div x-show="tab === 'informacion-general'" x-transition>
    <div class="max-w-md"> {{-- Le damos un ancho máximo para que no se vea gigante --}}
        
        <x-wire-native-select 
            label="Tipo de Sangre" 
            class="mb-4" 
            name="blood_type_id">
            
            <option value="">Selecciona un tipo de sangre</option>
            
            @foreach ($bloodTypes as $bloodType)
                <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                    {{ $bloodType->name }}
                </option>
            @endforeach

        </x-wire-native-select>

        <x-wire-textarea label="Observaciones" name="observations">
                {{ old('observations', $patient->observations) }}
                
</x-wire-textarea>
    </div>
</div>
            {{-- Contenido de Tab4: Contacto de emergencia --}}
<div x-show="tab === 'contacto-emergencia'" style="display: none;">
    <div class="space-y-4">
        <x-wire-input label="Nombre de contacto" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
        <x-wire-phone label="Teléfono de contacto" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" mask=" (###) ###—####" placeholder="(999) 999-999"/>
        <x-wire-input label="Relación con el paciente" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" placeholder="Ej: Familiar, amigo, etc." />
    </div>
</div> {{-- Fin Contenido Tabs --}}
 </div> {{-- Fin x-data --}}
                </x-wire-card>
            </form>
        </div>
    </div>
</x-admin-layout>