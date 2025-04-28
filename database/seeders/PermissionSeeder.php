<?php

namespace Database\Seeders;

use App\Enums\RoleNames;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // User permissions
            'list-users', 'view-users', 'create-users', 'update-users', 'delete-users',
            // More permissions can be added here
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $super_admin = Role::findByName(RoleNames::SUPER_ADMIN);
        $admin       = Role::findByName(RoleNames::ADMIN);
        $user        = Role::findByName(RoleNames::USER);

        // Assign all permissions to admin
        $super_admin->syncPermissions(Permission::all());

        // Assign all permissions to admin
        $admin->syncPermissions(Permission::all());

        // Assign specific permissions to user
        $user->syncPermissions(Permission::whereNotIn('name', ['delete-users'])->get());
    }
}
