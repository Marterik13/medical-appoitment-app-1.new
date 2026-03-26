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
        
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles')); 
    }

    public function store(Request $request)
    {
        // 1. Validación (incluye la regex del video para el id_number)
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

        // 2. Crear el usuario en la tabla 'users'
        $user = User::create($data);

        // 3. Asignar el rol usando el sistema de Spatie
        $role = Role::findById($data['rol_id']);
        $user->assignRole($role->name);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Bien hecho!',
            'text'  => 'El usuario se creó correctamente.',
        ]);

        // Si es Paciente, crear registro médico y redirigir
        if ($role->name === 'Paciente') {
            // Creamos el registro en la tabla 'patients' (id, user_id, timestamps)
            $patient = $user->patient()->create([]); 
            
            // Redirección inmediata al formulario de salud del paciente
            return redirect()->route('admin.patients.edit', $patient);
        }

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|unique:users,id_number,' . $user->id,
            'phone'     => 'required|digits_between:7,15',
            'address'   => 'required|string|max:255',
            'rol_id'    => 'required|exists:roles,id',
        ]); 

        if ($request->filled('password')) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Actualizamos datos básicos
        $user->update($data);
        
        // Sincronizamos el rol (esto quita el anterior y pone el nuevo)
        $user->roles()->sync($data['rol_id']);

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Actualizado!',
            'text'  => 'El usuario se actualizó correctamente.',
        ]);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        // Protección para que no te borres a ti mismo 
        if ($user->id === Auth::id()) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => '¡Acción denegada!',
                'text'  => 'No puedes eliminar el usuario con el que estás logueado.',
            ]);
            return redirect()->back();
        }

        // Limpiamos roles y eliminamos
        $user->roles()->detach();
        $user->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Eliminado',
            'text'  => 'El usuario ha sido borrado del sistema.',
        ]);

        return redirect()->route('admin.users.index');
    }
}