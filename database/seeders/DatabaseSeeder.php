<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void {
		
        // Llamar al seeder de roles
        $this->call(RolesSeeder::class);      
        // Llamar al seeder de permisos
        $this->call(PermissionsSeeder::class);

        // Crear usuario admin con contraseña y asignar rol
        $adminUser = User::firstOrCreate(
            ['email' => 'test@example.com'], // Buscar por email
            [
                'name' => 'Admin',
                'password' => Hash::make('password') // Utiliza Hash::make para la seguridad
            ]
        );

        // Asegúrate de que los roles están creados
        // Puedes necesitar ejecutar RoleSeeder aquí o asegurarte de que se ejecuta antes
        $adminRole = Role::findByName('Administración');

        // Asignar el rol de administrador al usuario si no lo tiene
        if (!$adminUser->hasRole($adminRole)) {
            $adminUser->assignRole($adminRole);
        }

        // Asignar permisos específicos si es necesario
        $permissions = [
            'ver documentos',
            'crear documentos',
            'editar documentos',
            'borrar documentos',
            'aprobar documentos',
            'rechazar documentos'
        ];

        // Comprobar y asignar permisos si el usuario no los tiene
        foreach ($permissions as $permission) {
            // Asegúrate de que el permiso existe antes de asignarlo
            if (Permission::where('name', $permission)->exists() && !$adminUser->hasPermissionTo($permission)) {
                $adminUser->givePermissionTo($permission);
            }
        }
    }
}
