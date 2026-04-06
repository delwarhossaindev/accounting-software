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

        $modules = [
            'accounts',
            'account-groups',
            'journals',
            'customers',
            'suppliers',
            'invoices',
            'payments',
            'expenses',
            'reports',
            'settings.users',
            'settings.roles',
            'settings.permissions',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        $permissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = "{$module}.{$action}";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Admin role — gets ALL permissions
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminRole->syncPermissions(Permission::all());

        // Accountant role — everything except settings
        $accountantRole = Role::firstOrCreate(['name' => 'Accountant', 'guard_name' => 'web']);
        $accountantRole->syncPermissions(
            Permission::where('name', 'not like', 'settings.%')->get()
        );

        // Viewer role — only view permissions
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'web']);
        $viewerRole->syncPermissions(
            Permission::where('name', 'like', '%.view')->get()
        );

        // Assign Admin role to default admin user (and mark email as verified)
        $admin = User::query()->where('email', 'admin@admin.com')->first();
        if ($admin) {
            if (is_null($admin->email_verified_at)) {
                $admin->email_verified_at = now();
                $admin->save();
            }
            $admin->assignRole($adminRole);
        }
    }
}
