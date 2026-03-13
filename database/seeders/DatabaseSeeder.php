<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this -> call
        ([
            ContestantSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Admin user
        User::factory()->create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'role' => 'admin', // 👈 override role
        ]);

        // Test guest user
        User::factory()->create([
        'name' => 'Guest User',
        'email' => 'guest@example.com',
        'role' => 'guest',
        ]);

         // Admin account
        User::factory()->role('admin')->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        // SAS account
        User::factory()->role('sas')->create([
            'name' => 'SAS User',
            'email' => 'sas@example.com',
        ]);

        // Judge account
        User::factory()->role('judge')->create([
            'name' => 'Judge User',
            'email' => 'judge@example.com',
        ]);

        // Default Guest account
        User::factory()->create([
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            // role defaults to 'guest'
        ]);

        // Optional: create multiple random guests
        User::factory(5)->create();
    }
}
