<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [

            [
                'guard' => 'web', 'role' => 'admin'
            ],

            [
                'guard' => 'web', 'role' => 'editor'
            ],

            [
                'guard' => 'web', 'role' => 'user'
            ]
        ];

        // Create roles
        foreach ($roles as $role) {
            Role::create(['name' => $role['role'], 'guard_name' => $role['guard']]);
        }
    }
}
