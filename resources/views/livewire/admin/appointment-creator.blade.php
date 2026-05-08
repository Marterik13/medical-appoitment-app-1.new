<div>
    <div class="mb-6">
        <div class="mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Buscar disponibilidad</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Encuentra el horario perfecto para tu cita.</p>
        </div>
        
        <div class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fecha</label>
                <div class="relative">
                    <input type="date" wire:model.live="searchDate" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                </div>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Hora</label>
                <select wire:model.live="searchTime" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white appearance-none">
                    <option value="">08:00 - 09:00</option>
                    <option value="08:00 - 12:00">Mañana (08:00 - 12:00)</option>
                    <option value="12:00 - 16:00">Tarde (12:00 - 16:00)</option>
                    <option value="16:00 - 20:00">Noche (16:00 - 20:00)</option>
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Especialidad (opcional)</label>
                <select wire:model.live="searchSpecialty" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white appearance-none">
                    <option value="">Endocrinología</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty }}">{{ $specialty }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex-1 min-w-[200px]">
                <button wire:click="searchAvailability" type="button" class="w-full text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none transition-colors">
                    Buscar disponibilidad
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        
        <!-- Área Izquierda: Resultados de Doctores -->
        <div class="md:col-span-8 space-y-4">
            @forelse($doctorsWithSlots as $item)
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium text-xl dark:bg-blue-900 dark:text-blue-200">
                            {{ substr($item['doctor']->name, strpos($item['doctor']->name, " ") + 1, 1) . substr($item['doctor']->name, strrpos($item['doctor']->name, " ") + 1, 1) ?: substr($item['doctor']->name, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item['doctor']->name }}</h3>
                            <p class="text-sm text-blue-600 dark:text-blue-400">{{ $item['doctor']->specialty }}</p>
                        </div>
                    </div>

                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3 font-medium">Horarios disponibles:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($item['slots'] as $slot)
                            <button type="button" wire:click="selectSlot({{ $item['doctor']->id }}, '{{ $slot }}')" 
                                    class="px-6 py-2 text-sm rounded-md transition-colors font-medium border-0
                                    {{ $selectedDoctorId == $item['doctor']->id && $selectedTime == $slot 
                                        ? 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md' 
                                        : 'bg-[#a5b4fc] text-white hover:bg-[#818cf8] dark:bg-indigo-800 dark:hover:bg-indigo-700' }}">
                                {{ $slot }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @empty
                @if($searchDate)
                    <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 text-center dark:bg-gray-800 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">No hay doctores disponibles para los criterios seleccionados.</p>
                    </div>
                @else
                    <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100 text-center dark:bg-gray-800 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">Seleccione una fecha para buscar disponibilidad.</p>
                    </div>
                @endif
            @endforelse
        </div>

        <!-- Área Derecha: Resumen de la Cita -->
        <div class="md:col-span-4">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 sticky top-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Resumen de la cita</h3>
                
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 dark:text-gray-500 font-medium">Doctor:</span>
                        <span class="font-medium text-gray-900 dark:text-white text-right">{{ $this->selectedDoctor ? $this->selectedDoctor->name : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 dark:text-gray-500 font-medium">Fecha:</span>
                        <span class="font-medium text-gray-900 dark:text-white text-right">{{ $searchDate ? \Carbon\Carbon::parse($searchDate)->format('Y-m-d') : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 dark:text-gray-500 font-medium">Horario:</span>
                        <span class="font-medium text-gray-900 dark:text-white text-right">
                            {{ $selectedTime ? $selectedTime . ' - ' . \Carbon\Carbon::parse($selectedTime)->addMinutes(15)->format('H:i:s') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400 dark:text-gray-500 font-medium">Duración:</span>
                        <span class="font-medium text-gray-900 dark:text-white text-right">15 minutos</span>
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Paciente</label>
                    <select wire:model="patient_id" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white appearance-none">
                        <option value="">Seleccione paciente...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ optional($patient->user)->name }}</option>
                        @endforeach
                    </select>
                    @error('patient_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mb-6">
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Motivo de la cita</label>
                    <textarea wire:model="reason" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-white rounded-lg border border-indigo-500 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" placeholder="Ej: Chequeo de medicamentos"></textarea>
                </div>
                
                @error('selectedDoctorId') <div class="text-red-500 text-xs mb-2">Debe seleccionar un horario y doctor.</div> @enderror

                <button type="button" wire:click="confirmAppointment" class="w-full text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-3 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800 transition-colors" {{ !$selectedDoctorId || !$selectedTime || !$patient_id ? 'disabled' : '' }}>
                    Confirmar cita
                </button>
            </div>
        </div>
    </div>
</div>
