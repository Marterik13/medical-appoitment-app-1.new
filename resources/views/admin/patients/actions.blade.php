<div class="flex items-center justify-center">
    {{-- Botón de Editar: Cuadrado morado con icono blanco (Igual a image_2.png) --}}
    <a href="{{ route('admin.patients.edit', $patient) }}" 
       class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all duration-200">
        <i class="fa-solid fa-pen-to-square text-base"></i>
    </a>

    
</div>