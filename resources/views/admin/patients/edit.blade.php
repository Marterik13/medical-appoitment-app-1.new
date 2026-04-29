{{-- 1. Lógica para detectar errores y pestaña inicial --}}
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
            
            {{-- Breadcrumbs --}}
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

                {{-- Encabezado con Foto y Botones --}}
                <x-wire-card class="mb-6">
                    <div class="lg:flex lg:justify-between lg:items-center">
                        <div class="flex items-center">
                            <img src="{{$patient->user->profile_photo_url}}" class="h-20 w-20 rounded-full object-cover shadow-md">
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">{{$patient->user->name }}</p>
                            </div>
                        </div>
                        <div class="flex space-x-3 mt-6 lg:mt-0">
                            <x-wire-button outline gray href="{{ route('admin.patients.index') }}">Volver</x-wire-button>
                            <x-wire-button type="submit" primary label="Guardar cambios" />
                        </div>
                    </div>
                </x-wire-card>

                {{-- Implementación de Componentes (ADA 6) --}}
                <x-wire-card>
                    <x-tabs :active="$initialTab">
                        
                        <x-slot name="header">
                            <x-tab-link name="datos-personales">
                                <i class="fa-solid fa-user me-2"></i> Datos personales
                            </x-tab-link>

                            <x-tab-link name="antecedentes" :error="$errors->hasAny($errorGroups['antecedentes'])">
                                <i class="fa-solid fa-file-lines me-2"></i> Antecedentes
                            </x-tab-link>

                            <x-tab-link name="informacion-general" :error="$errors->hasAny($errorGroups['informacion-general'])">
                                <i class="fa-solid fa-info me-2"></i> Información general
                            </x-tab-link>

                            <x-tab-link name="contacto-emergencia" :error="$errors->hasAny($errorGroups['contacto-emergencia'])">
                                <i class="fa-solid fa-heart me-2"></i> Contacto de emergencia
                            </x-tab-link>
                        </x-slot>

                        {{-- 1. DATOS PERSONALES (Recuperado cuadro azul y dirección) --}}
                        <x-tab-content name="datos-personales">
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
                        </x-tab-content>

                        {{-- 2. ANTECEDENTES (Los 4 campos completos) --}}
                        <x-tab-content name="antecedentes">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-wire-textarea label="Alergias conocidas" name="allergies">
                                    {{ old('allergies', $patient->allergies) }}
                                </x-wire-textarea>
                                <x-wire-textarea label="Patológicos" name="chronic_conditions">
                                    {{ old('chronic_conditions', $patient->chronic_conditions) }}
                                </x-wire-textarea>
                                <x-wire-textarea label="Quirúrgicos" name="surgical_history">
                                    {{ old('surgical_history', $patient->surgical_history) }}
                                </x-wire-textarea>
                                <x-wire-textarea label="Familiares" name="family_history">
                                    {{ old('family_history', $patient->family_history) }}
                                </x-wire-textarea>
                            </div>
                        </x-tab-content>

                        {{-- 3. INFORMACIÓN GENERAL --}}
                        <x-tab-content name="informacion-general">
                            <div class="max-w-md">
                                <x-wire-native-select label="Tipo de Sangre" name="blood_type_id" class="mb-4">
                                    <option value="">Selecciona...</option>
                                    @foreach ($bloodTypes as $bloodType)
                                        <option value="{{ $bloodType->id }}" @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>{{ $bloodType->name }}</option>
                                    @endforeach
                                </x-wire-native-select>
                                <x-wire-textarea label="Observaciones" name="observations">
                                    {{ old('observations', $patient->observations) }}
                                </x-wire-textarea>
                            </div>
                        </x-tab-content>

                        {{-- 4. EMERGENCIA (Recuperados todos los campos) --}}
                        <x-tab-content name="contacto-emergencia">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <x-wire-input label="Nombre de contacto" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" />
                                <x-wire-phone label="Teléfono de contacto" name="emergency_contact_phone" mask="(###) ###-####" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" />
                                <div class="md:col-span-2">
                                    <x-wire-input label="Relación / Parentesco" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}" />
                                </div>
                            </div>
                        </x-tab-content>

                    </x-tabs>
                </x-wire-card>
            </form>
        </div>
    </div>
</x-admin-layout>