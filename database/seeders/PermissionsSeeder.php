<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos
        $permissions = [
            'ver documentos',
            'crear documentos',
            'editar documentos',
            'borrar documentos',
            'aprobar documentos',
            'rechazar documentos'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos a roles
        $admin = Role::where('name', 'Administración')->first();
        $admin->givePermissionTo(Permission::all());

        $responsable = Role::where('name', 'Responsable')->first();
        $responsable->givePermissionTo(['ver documentos', 'crear documentos', 'aprobar documentos', 'rechazar documentos']);

        $asignado = Role::where('name', 'Asignado')->first();
        $asignado->givePermissionTo(['ver documentos']);
    }
}
