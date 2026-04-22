{{-- 1. Lógica inicial para determinar la pestaña por defecto y detectar errores --}}
@php
    $errorGroups = [
        'antecedentes' => ['allergies', 'chronic_conditions', 'surgical_history', 'family_history'],
        'informacion-general' => ['blood_type_id', 'observations'],
        'contacto-emergencia' => ['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'],
    ];

    $initialTab = 'datos-personales';
    foreach ($errorGroups as $tabName => $fields) {
        if ($errors->hasAny($fields)) {
            $initialTab = $tabName;
            break;
        }
    }
@endphp

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

            <h1 class="text-2xl font-bold text-gray-800 mb-8">Editar Expediente</h1>

            <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ENCABEZADO: Foto y Botones de Acción --}}
                <x-wire-card class="mb-6">
                    <div class="lg:flex lg:justify-between lg:items-center">
                        <div class="flex items-center">
                            <img src="{{$patient->user->profile_photo_url}}" alt="{{ $patient->user->name }}" class="h-20 w-20 rounded-full object-cover shadow-md">
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">{{$patient->user->name }}</p>
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

                {{-- NAVEGACIÓN POR TABS CON HOVER CORREGIDO --}}
                <x-wire-card>
                    <div x-data="{ tab: '{{ $initialTab }}' }">
                        
                        <div class="border-b border-gray-200">
                            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                                
                                {{-- Tab 1: Datos Personales --}}
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'datos-personales'" 
                                       :class="tab === 'datos-personales' ? 'text-blue-600 border-blue-600 active' : 'text-gray-500 border-transparent hover:text-blue-600 hover:border-gray-300'"
                                       class="inline-flex items-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                        <i class="fa-solid fa-user me-2"></i> Datos personales
                                    </a>
                                </li>

                                {{-- Tab 2: Antecedentes (Hover agregado a la lógica) --}}
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'antecedentes'" 
                                       :class="tab === 'antecedentes' ? 'text-blue-600 border-blue-600 active' : '{{ $errors->hasAny($errorGroups['antecedentes']) ? 'text-red-600 hover:text-red-800' : 'text-gray-500 hover:text-blue-600 hover:border-gray-300' }} border-transparent'"
                                       class="inline-flex items-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                        <i class="fa-solid fa-file-lines me-2"></i>
                                        @if($errors->hasAny($errorGroups['antecedentes']))
                                            <span class="text-red-600 font-bold">Antecedentes (!)</span>
                                        @else
                                            Antecedentes
                                        @endif
                                    </a>
                                </li>

                                {{-- Tab 3: Información General (Hover agregado a la lógica) --}}
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'informacion-general'" 
                                       :class="tab === 'informacion-general' ? 'text-blue-600 border-blue-600 active' : '{{ $errors->hasAny($errorGroups['informacion-general']) ? 'text-red-600 hover:text-red-800' : 'text-gray-500 hover:text-blue-600 hover:border-gray-300' }} border-transparent'"
                                       class="inline-flex items-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                        <i class="fa-solid fa-info me-2"></i>
                                        @if($errors->hasAny($errorGroups['informacion-general']))
                                            <span class="text-red-600 font-bold">Información general (!)</span>
                                        @else
                                            Información general
                                        @endif
                                    </a>
                                </li>

                                {{-- Tab 4: Contacto de Emergencia (Hover agregado a la lógica) --}}
                                <li class="me-2">
                                    <a href="#" x-on:click.prevent="tab = 'contacto-emergencia'" 
                                       :class="tab === 'contacto-emergencia' ? 'text-blue-600 border-blue-600 active' : '{{ $errors->hasAny($errorGroups['contacto-emergencia']) ? 'text-red-600 hover:text-red-800' : 'text-gray-500 hover:text-blue-600 hover:border-gray-300' }} border-transparent'"
                                       class="inline-flex items-center p-4 border-b-2 rounded-t-lg transition-colors duration-200">
                                        <i class="fa-solid fa-heart me-2"></i>
                                        @if($errors->hasAny($errorGroups['contacto-emergencia']))
                                            <span class="text-red-600 font-bold">Emergencia (!)</span>
                                        @else
                                            Contacto de emergencia
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>

                        {{-- CONTENIDO DE LOS TABS --}}
                        <div class="px-4 mt-8">
                            
                            {{-- 1. TAB: DATOS PERSONALES --}}
                            <div x-show="tab === 'datos-personales'" x-transition>
                                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fa-solid fa-user-gear text-blue-500 text-xl"></i>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-bold text-blue-800">Edición de cuenta</h3>
                                            <p class="text-xs text-blue-600">La información de acceso se gestiona desde el perfil de usuario asociado.</p>
                                        </div>
                                    </div>
                                    <x-wire-button primary sm label="Editar usuario" target="_blank" href="{{ route('admin.users.edit', $patient->user) }}" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <p><span class="text-gray-500 font-semibold uppercase text-xs">Teléfono:</span> <span class="ml-1 font-bold text-gray-900">{{ $patient->user->phone ?? 'N/A' }}</span></p>
                                    <p><span class="text-gray-500 font-semibold uppercase text-xs">Email:</span> <span class="ml-1 font-bold text-gray-900">{{ $patient->user->email }}</span></p>
                                    <p class="md:col-span-2"><span class="text-gray-500 font-semibold uppercase text-xs">Dirección:</span> <span class="ml-1 font-bold text-gray-900">{{ $patient->user->address ?? 'No especificada' }}</span></p>
                                </div>
                            </div>

                            {{-- 2. TAB: ANTECEDENTES --}}
                            <div x-show="tab === 'antecedentes'" x-transition>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <x-wire-textarea label="Alergias conocidas" name="allergies">
                                        {{ old('allergies', $patient->allergies) }}
                                    </x-wire-textarea>
                                    <x-wire-textarea label="Antecedentes Patológicos" name="chronic_conditions">
                                        {{ old('chronic_conditions', $patient->chronic_conditions) }}
                                    </x-wire-textarea>
                                    <x-wire-textarea label="Antecedentes Quirúrgicos" name="surgical_history">
                                        {{ old('surgical_history', $patient->surgical_history) }}
                                    </x-wire-textarea>
                                    <x-wire-textarea label="Antecedentes Familiares" name="family_history">
                                        {{ old('family_history', $patient->family_history) }}
                                    </x-wire-textarea>
                                </div>
                            </div>

                            {{-- 3. TAB: INFORMACIÓN GENERAL --}}
                            <div x-show="tab === 'informacion-general'" x-transition>
                                <div class="max-w-md">
                                    <x-wire-native-select label="Tipo de Sangre" class="mb-6" name="blood_type_id">
                                        <option value="">Selecciona un tipo de sangre</option>
                                        @foreach ($bloodTypes as $bloodType)
                                            <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                                {{ $bloodType->name }}
                                            </option>
                                        @endforeach
                                    </x-wire-native-select>
                                    <x-wire-textarea label="Observaciones Médicas" name="observations" rows="5">
                                        {{ old('observations', $patient->observations) }}
                                    </x-wire-textarea>
                                </div>
                            </div>

                            {{-- 4. TAB: CONTACTO DE EMERGENCIA --}}
                            <div x-show="tab === 'contacto-emergencia'" x-transition>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <x-wire-input label="Nombre de contacto" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
                                    <x-wire-input label="Teléfono de contacto" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" />
                                    <div class="md:col-span-2">
                                        <x-wire-input label="Relación / Parentesco" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
                                    </div>
                                </div>
                            </div>

                        </div> {{-- Fin Contenido Tabs --}}
                    </div> {{-- Fin x-data --}}
                </x-wire-card>
            </form>
        </div>
    </div>
</x-admin-layout>