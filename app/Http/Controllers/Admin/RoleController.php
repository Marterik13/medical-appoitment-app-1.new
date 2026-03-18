<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Esta importación es vital para que Laravel reconozca el modelo Role
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Muestra el listado de roles.
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

        Role::create([
            'name' => $request->name
        ]);

        return redirect()->route('admin.roles.index')->with('swal', [
            'icon' => 'success',
            'title' => '¡Buen trabajo!',
            'text' => 'El rol se ha creado correctamente.'
        ]);
    }

    /**
     * Muestra el formulario de edición con los datos del rol.
     */
    public function edit(Role $role)
    {
        // El error estaba aquí: compact recibe el nombre como string 'role'
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Actualiza el rol en la base de datos.
     */
    public function update(Request $request, Role $role)
    {
        // Validamos que el nombre sea requerido y único, 
        // pero ignorando el ID del rol que estamos editando.
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id
        ]);

        $role->update([
            'name' => $request->name
        ]);

        // Redirecciona de vuelta a la edición con la alerta de éxito
        return redirect()->route('admin.roles.edit', $role)->with('swal', [
            'icon' => 'success',
            'title' => '¡Actualizado!',
            'text' => 'El rol se ha actualizado correctamente.'
        ]);
    }

    /**
     * Elimina el rol (lógica para el siguiente video).
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('admin.roles.index')->with('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'El rol se ha eliminado correctamente.'
        ]);
    }
}