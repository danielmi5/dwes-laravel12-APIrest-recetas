<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Class RoleSeeder
 *
 * Crea roles iniciales usados por la aplicación en entorno de desarrollo/tests.
 *
 * @package Database\Seeders
 */
class RoleSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Creamos roles con el guard de Sanctum (tokens), no con web.
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'sanctum',
        ]);

        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'sanctum',
        ]);
    }
}
