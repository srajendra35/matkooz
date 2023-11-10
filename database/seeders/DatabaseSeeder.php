<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Admin::updateOrCreate([
            'name' => 'Admin',
        ], [
            'name' => 'Admin',
            'email' => 'admin@123gmail.com',
            'phone' => '',
            'password' => Hash::make('admin@123'),
        ]);

        $this->call(UserSeeder::class);
        \App\Models\User::factory(10000)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
