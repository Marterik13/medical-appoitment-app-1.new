<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles')); 
    }

    public function store(Request $request)
    {
        // Validación exacta del video: nótese la regex y los dígitos del teléfono
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9-]+$/|unique:users',
            'phone'     => 'required|digits_between:7,15',
            'address'   => 'required|string|max:255',
            'rol_id'    => 'required|exists:roles,id',
        ]); 

        $data['password'] = bcrypt($data['password']);

        // Crear el usuario
        $user = User::create($data);

        // El profe usa roles()->attach para la creación inicial
        $user->roles()->attach($data['rol_id']);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Bien hecho!',
            'text'  => 'El usuario se creó correctamente.',
        ]);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all(); // El profe también pasa los roles a la vista de edición
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update: Aquí es donde el video hace los cambios importantes
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            // Se ignora el ID del usuario actual para permitir que mantenga su mismo correo
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            // La contraseña es opcional en la edición (nullable)
            'password'  => 'nullable|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|unique:users,id_number,' . $user->id,
            'phone'     => 'required|digits_between:7,15',
            'address'   => 'required|string|max:255',
            'rol_id'    => 'required|exists:roles,id',
        ]); 

        // Si la contraseña viene vacía, la eliminamos del array para no sobreescribir con nulo
        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Actualizar datos del usuario
        $user->update($data);

        // CLAVE DEL VIDEO: Usar sync para reemplazar el rol anterior por el nuevo
        $user->roles()->sync($data['rol_id']);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'text'  => 'El usuario se actualizó correctamente.',
        ]);

        return redirect()->route('admin.users.index');
    }

   
     public function destroy(User $user){

        if ($user->id === auth::user()->id) {
         session()->flash('swal', [
             'icon'  => 'error',
             'title' => '¡Error!',
             'text'  => 'No puedes eliminar tu propio usuario.',
         ]);

         abort(403, 'No puedes borrar tu propio usuario.');
        }

    // 1. Eliminar roles asociados al usuario en la tabla pivote
    $user->roles()->detach();

    // 2. Eliminar al usuario de la base de datos
    $user->delete();

    // 3. Lanzar la alerta de éxito para SweetAlert2
    session()->flash('swal', [
        'icon'  => 'success',
        'title' => 'Usuario eliminado',
        'text'  => 'El usuario ha sido eliminado correctamente.',
    ]);

    return redirect()->route('admin.users.index');
}
    }
