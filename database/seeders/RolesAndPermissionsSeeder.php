<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'settings.users.view',
            'settings.users.create',
            'settings.users.edit',
            'settings.users.delete',

            'settings.roles.view',
            'settings.roles.create',
            'settings.roles.edit',
            'settings.roles.delete',

            'settings.permissions.view',
            'settings.permissions.create',
            'settings.permissions.edit',
            'settings.permissions.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions($permissions);

        $admin = User::query()->where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole($adminRole);
        }
    }
}

