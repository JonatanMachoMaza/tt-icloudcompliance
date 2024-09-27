<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder {
    public function run() {
        Role::create(['name' => 'Administración']);
        Role::create(['name' => 'Responsable']);
        Role::create(['name' => 'Asignado']);
    }
}
