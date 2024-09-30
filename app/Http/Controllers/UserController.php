<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Mostrar lista de usuarios
	public function index(Request $request)
	{
		$query = User::query();

		// Filtrado por búsqueda
		if ($request->filled('search')) {
			$search = $request->input('search');
			$query->where(function ($q) use ($search) {
				$q->where('name', 'like', '%' . $search . '%')
				  ->orWhere('email', 'like', '%' . $search . '%');
			});
		}

		// Ordenación
		if ($request->filled('sort')) {
			$sort = $request->input('sort');
			$query->orderBy($sort, 'asc');
		}

		// Obtener usuarios paginados
		$users = $query->paginate(10); // Cambia 10 por el número de resultados que desees por página

		return view('users.index', compact('users'));
	}



    // Mostrar formulario para crear un nuevo usuario
    public function create()
    {
        $roles = Role::all(); // Obtener todos los roles
        return view('users.create', compact('roles'));
    }

    // Almacenar un nuevo usuario
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name', // Validar que el rol exista
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        // Crear nuevo usuario
        $adminUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar el rol al usuario
        $adminUser->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente');
    }

    // Mostrar el formulario para editar un usuario
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all(); // Obtener todos los roles
        $userRoles = $user->getRoleNames(); // Obtener los roles del usuario

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name', // Validar que el rol exista
        ]);

        if ($validator->fails()) {
            return redirect()->route('users.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        // Actualizar los datos del usuario
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Asignar el rol al usuario
        $user->syncRoles([$request->role]); // Sincronizar el rol

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente');
    }

    // Mostrar los detalles de un usuario
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente');
    }

    // Deshabilitar un usuario
    public function disable($id)
    {
        $user = User::findOrFail($id);
        $user->active = false; // Asumiendo que hay un campo 'active' en el modelo User
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuario deshabilitado exitosamente');
    }
}
