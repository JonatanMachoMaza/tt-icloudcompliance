<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder {
    public function run() {
        Role::firstOrCreate(['name' => 'Administración']);
        Role::firstOrCreate(['name' => 'Responsable']);
        Role::firstOrCreate(['name' => 'Asignado']);
    }
}
