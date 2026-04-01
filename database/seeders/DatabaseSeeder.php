<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // Roles & permissions
        $this->call(RolesAndPermissionsSeeder::class);

        // Seed default chart of accounts
        $this->call(DefaultAccountSeeder::class);

        // Seed demo data
        $this->call(DemoDataSeeder::class);
    }
}
