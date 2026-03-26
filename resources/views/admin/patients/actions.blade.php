<div class="flex items-center justify-center">
    {{-- Botón de Editar: Cuadrado morado con icono blanco (Igual a image_2.png) --}}
    <a href="{{ route('admin.patients.edit', $patient) }}" 
       class="inline-flex items-center justify-center w-10 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-sm transition-all duration-200">
        <i class="fa-solid fa-pen-to-square text-base"></i>
    </a>

    {{-- 
        Si quieres mantener el de eliminar aunque no salga en la foto, 
        te sugiero este estilo para que no rompa el diseño:
    --}}
    {{-- 
    <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" class="ml-2" onsubmit="return confirm('¿Eliminar paciente?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="inline-flex items-center justify-center w-10 h-10 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-all">
            <i class="fa-solid fa-trash-can text-sm"></i>
        </button>
    </form> 
    --}}
</div>