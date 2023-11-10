<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::updateOrCreate([
            'email' => 'jitendra@gmail.com',
        ], [
            'first_name' => 'jitendra',
            'last_name' => 'saini',
            'phone' => '9876543210',
            // 'role_id' => '1',
            'email' => 'jitendra@gmail.com',
            'password' => Hash::make('jitendra@123'),
        ]);
    }
}
