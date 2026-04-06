<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user (email verified)
        \App\Models\User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Roles & permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Seed default chart of accounts
        $this->call(DefaultAccountSeeder::class);

        // Seed demo data
        $this->call(DemoDataSeeder::class);
    }
}
