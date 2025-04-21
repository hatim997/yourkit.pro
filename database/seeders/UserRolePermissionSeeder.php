<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        Permission::create(['name' => 'view role']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'update role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'view permission']);
        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'update permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'view archived user']);
        Permission::create(['name' => 'create archived user']);
        Permission::create(['name' => 'update archived user']);
        Permission::create(['name' => 'delete archived user']);

        Permission::create(['name' => 'view setting']);
        Permission::create(['name' => 'create setting']);
        Permission::create(['name' => 'update setting']);
        Permission::create(['name' => 'delete setting']);


        // Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // give all permissions to super-admin role.
        $allPermissionNames = Permission::pluck('name')->toArray();

        $adminRole->givePermissionTo($allPermissionNames);


        // Create User and assign Role to it.

        $adminUser = User::firstOrCreate([
                    'email' => 'admin@gmail.com',
                ], [
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'password' => Hash::make ('12345678'),
                    'phone' => '9830042699',
                    'email_verified_at' => now(),
                ]);

        $adminUser->assignRole($adminRole);

        $adminProfile = $adminUser->profile()->firstOrCreate([
            'user_id' => $adminUser->id,
        ], [
            'user_id' => $adminUser->id,
            'first_name' => $adminUser->name,
        ]);
    }
}
