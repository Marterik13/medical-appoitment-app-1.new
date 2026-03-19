<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Muestra la lista de roles.
     */
    public function index()
    {
        return view('admin.roles.index');
    }

    /**
     * Muestra el formulario para crear un nuevo rol.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Almacena un nuevo rol en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create(['name' => $request->name]);

        // Alerta de éxito para SweetAlert2
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Bien hecho!',
            'text'  => 'El rol se creó correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Muestra el formulario para editar un rol existente.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Actualiza el rol en la base de datos.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update(['name' => $request->name]);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'text'  => 'El rol se actualizó correctamente.',
        ]);

        return redirect()->route('admin.roles.index');
    }

    /**
     * Elimina un rol de la base de datos.
     */
    public function destroy(Role $role)
    {
        $role->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Eliminado',
            'text'  => 'El rol ha sido eliminado con éxito.',
        ]);

        return redirect()->route('admin.roles.index');
    }
}