<div>
    <!-- Header Area (Patient Info & Action Buttons) -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ optional($appointment->patient->user)->name ?? 'Paciente Desconocido' }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                DNI: {{ optional($appointment->patient->user)->id_number ?? 'N/A' }}
            </p>
        </div>
        <div class="flex space-x-3">
            <button wire:click="openMedicalHistoryModal" type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-file-medical mr-2 text-gray-500"></i> Ver Historia
            </button>
            <button wire:click="openHistoryModal" type="button" class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-clock-rotate-left mr-2 text-gray-500"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 dark:bg-gray-800 dark:border-gray-700 overflow-hidden">
        
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 pt-2">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                <li class="me-6" role="presentation">
                    <button wire:click="setActiveTab('consulta')" class="inline-block pb-4 pt-4 border-b-2 font-medium text-base transition-colors {{ $activeTab === 'consulta' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fa-solid fa-stethoscope mr-2"></i> Consulta
                    </button>
                </li>
                <li class="me-6" role="presentation">
                    <button wire:click="setActiveTab('receta')" class="inline-block pb-4 pt-4 border-b-2 font-medium text-base transition-colors {{ $activeTab === 'receta' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300' }}">
                        <i class="fa-solid fa-prescription-bottle-medical mr-2"></i> Receta
                    </button>
                </li>
            </ul>
        </div>

        <div class="p-6">
            @if($activeTab === 'consulta')
                <div class="space-y-6">
                    <div>
                        <label for="diagnosis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Diagnóstico</label>
                        <textarea wire:model="diagnosis" id="diagnosis" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-indigo-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-colors dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                    </div>
                    <div>
                        <label for="treatment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tratamiento</label>
                        <textarea wire:model="treatment" id="treatment" rows="4" class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-colors dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                    </div>
                    <div>
                        <label for="notes" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Notas</label>
                        <textarea wire:model="notes" id="notes" rows="3" class="block p-3 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-200 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition-colors dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                    </div>
                </div>
            @endif

            @if($activeTab === 'receta')
                <div class="space-y-4">
                    <div class="flex gap-4 border-b border-gray-100 pb-2 mb-4 dark:border-gray-700">
                        <div class="w-5/12 text-sm font-medium text-gray-500 dark:text-gray-400">Medicamento</div>
                        <div class="w-3/12 text-sm font-medium text-gray-500 dark:text-gray-400">Dosis</div>
                        <div class="w-3/12 text-sm font-medium text-gray-500 dark:text-gray-400">Frecuencia / Duración</div>
                        <div class="w-1/12"></div>
                    </div>

                    @foreach($medications as $index => $medication)
                        <div class="flex gap-4 items-center">
                            <div class="w-5/12">
                                <input type="text" wire:model="medications.{{ $index }}.name" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ej. Amoxicilina 500mg">
                            </div>
                            <div class="w-3/12">
                                <input type="text" wire:model="medications.{{ $index }}.dose" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ej. 1 tableta">
                            </div>
                            <div class="w-3/12">
                                <input type="text" wire:model="medications.{{ $index }}.frequency" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white" placeholder="Ej. cada 8 horas por 7 días">
                            </div>
                            <div class="w-1/12 flex justify-center">
                                <button wire:click="removeMedication({{ $index }})" type="button" class="text-white bg-red-500 hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-10 h-10 inline-flex items-center justify-center shadow-sm transition-colors dark:bg-red-600 dark:hover:bg-red-700">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <div class="pt-4">
                        <button wire:click="addMedication" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                            <i class="fa-solid fa-plus mr-2 text-gray-500"></i> Añadir Medicamento
                        </button>
                    </div>
                </div>
            @endif
        </div>
        
        <div class="bg-white border-t border-gray-100 p-6 flex justify-end dark:bg-gray-800 dark:border-gray-700">
            <button wire:click="save" type="button" class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-6 py-2.5 shadow-sm transition-colors flex items-center dark:bg-indigo-600 dark:hover:bg-indigo-700">
                <i class="fa-solid fa-save mr-2"></i> Guardar Consulta
            </button>
        </div>
    </div>

    <!-- Modal Consultas Anteriores -->
    @if($showHistoryModal)
        <div class="relative z-[60]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>
            
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl dark:bg-gray-800">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 md:p-5 border-b dark:border-gray-700 bg-white dark:bg-gray-800">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Consultas Anteriores
                            </h3>
                            <button wire:click="closeHistoryModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Cerrar modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 md:p-6 space-y-4 max-h-[70vh] overflow-y-auto bg-gray-50 dark:bg-gray-900">
                            @forelse($pastConsultations as $index => $past)
                                <div class="bg-white rounded-lg border {{ $index === 0 ? 'border-indigo-300 shadow-sm' : 'border-gray-200' }} dark:bg-gray-800 dark:border-gray-700 mb-4 p-5">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h4 class="text-sm font-bold text-indigo-700 dark:text-indigo-400 flex items-center">
                                                <i class="fa-regular fa-calendar mr-2"></i> 
                                                {{ \Carbon\Carbon::parse($past->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($past->start_time)->format('H:i') }}
                                            </h4>
                                            <p class="text-sm text-gray-500 mt-1 dark:text-gray-400">Atendido por: Dr(a). {{ optional($past->doctor)->name }}</p>
                                        </div>
                                        <button type="button" class="px-4 py-2 text-sm font-medium text-indigo-600 bg-white border border-indigo-600 rounded-lg hover:bg-indigo-50 focus:ring-4 focus:outline-none focus:ring-indigo-300 transition-colors dark:bg-gray-800 dark:text-indigo-400 dark:border-indigo-500 dark:hover:bg-gray-700">
                                            Consultar Detalle
                                        </button>
                                    </div>
                                    
                                    <div class="text-sm text-gray-700 dark:text-gray-300 space-y-2 mt-4 bg-gray-50 p-4 rounded-md border border-gray-100 dark:bg-gray-900 dark:border-gray-700">
                                        <p><span class="font-bold text-gray-900 dark:text-white">Diagnóstico:</span> {{ $past->diagnosis ?: 'No especificado' }}</p>
                                        <p><span class="font-bold text-gray-900 dark:text-white">Tratamiento:</span> {{ $past->treatment ?: 'No especificado' }}</p>
                                        <p><span class="font-bold text-gray-900 dark:text-white">Notas:</span> {{ $past->notes ?: 'No especificado' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                                    No se encontraron consultas anteriores para este paciente.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Historia Médica -->
    @if($showMedicalHistoryModal)
        <div class="relative z-[60]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>
            
            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-5xl dark:bg-gray-800">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-5 border-b dark:border-gray-700 bg-white dark:bg-gray-800">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Historia médica del paciente
                            </h3>
                            <button wire:click="closeMedicalHistoryModal" type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                </svg>
                                <span class="sr-only">Cerrar modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 bg-white dark:bg-gray-800">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 mt-2">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de sangre:</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white">
                                        {{ optional($appointment->patient->bloodType)->name ?? 'No registrado' }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Alergias:</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white break-words">
                                        {{ $appointment->patient->allergies ?: 'No registradas' }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Enfermedades crónicas:</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white break-words">
                                        {{ $appointment->patient->chronic_conditions ?: 'No registradas' }}
                                    </p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Antecedentes quirúrgicos:</h4>
                                    <p class="text-base font-bold text-gray-900 dark:text-white break-words">
                                        {{ $appointment->patient->surgical_history ?: 'No registrados' }}
                                    </p>
                                </div>
                            </div>
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-6 pb-2 text-center">
                                <a href="{{ route('admin.patients.edit', $appointment->patient_id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-500 font-semibold text-sm transition-colors inline-block">
                                    Ver / Editar Historia Médica
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
