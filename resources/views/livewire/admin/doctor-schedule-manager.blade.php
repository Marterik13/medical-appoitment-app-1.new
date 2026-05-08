<div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800 p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Gestor de horarios</h2>
            <button wire:click="save" type="button" class="text-white bg-indigo-500 hover:bg-indigo-600 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-6 py-2.5 dark:bg-indigo-600 dark:hover:bg-indigo-700 focus:outline-none">
                Guardar horario
            </button>
        </div>

        <div class="overflow-x-auto relative mt-8">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-400 uppercase bg-transparent dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="py-4 px-6 text-left font-medium">DÍA/HORA</th>
                        @foreach($days as $dayId => $dayName)
                            <th scope="col" class="py-4 px-6 text-left font-medium">{{ $dayName }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($hours as $hour)
                        <tr class="bg-transparent border-b border-gray-100 dark:border-gray-700">
                            <!-- Hora de inicio de la fila -->
                            <td class="py-6 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white align-top w-48">
                                <div class="flex items-center space-x-3 mt-9">
                                    <input type="checkbox" disabled class="w-4 h-4 text-gray-300 bg-gray-50 border-gray-200 rounded focus:ring-0 cursor-not-allowed">
                                    <span class="text-base font-bold text-gray-700 dark:text-gray-300">{{ $hour }}:00:00</span>
                                </div>
                            </td>
                            
                            @foreach($days as $dayId => $dayName)
                                <td class="py-6 px-6 align-top">
                                    <div class="flex flex-col space-y-3">
                                        <!-- Checkbox TODOS -->
                                        <label class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 cursor-pointer">
                                            <input type="checkbox" wire:click="toggleHourAll({{ $dayId }}, '{{ $hour }}')" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                            <span class="ml-3">Todos</span>
                                        </label>
                                        
                                        <!-- Specific 15 min slots -->
                                        @foreach($intervals as $index => $min)
                                            @php
                                                $currentSlotTime = $hour . ':' . $min;
                                                $endSlotTime = \Carbon\Carbon::parse($currentSlotTime)->addMinutes(15)->format('H:i');
                                                $slotValue = $dayId . '_' . $currentSlotTime;
                                            @endphp
                                            <label class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                                                <input type="checkbox" wire:model="selectedSlots" value="{{ $slotValue }}" class="w-4 h-4 text-indigo-600 bg-white border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <span class="ml-3">{{ $currentSlotTime }} - {{ $endSlotTime }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
