<div class="flex items-center gap-2">
  {{-- Botón Editar --}}
  <x-wire-button href="{{route('admin.users.edit', $user)}}" blue>
    <i class="fa-solid fa-pen-to-square"></i>
  </x-wire-button>

  {{-- Botón Eliminar --}}
  <form action="{{route('admin.users.destroy', $user)}}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    <x-wire-button type="submit" red>
      <i class="fa-solid fa-trash"></i>
    </x-wire-button>
  </form>
</div>