<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@chatbot.ai',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
            'email_verified_at' => now(),
        ]);

        // Create Demo Customer
        User::create([
            'name' => 'Demo Customer',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
            'is_super_admin' => false,
            'email_verified_at' => now(),
        ]);
    }
}

