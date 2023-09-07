<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@qkcreators.com',
            'role' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make(12345678),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@qkcreators.com',
            'role' => 0,
            'email_verified_at' => now(),
            'password' => Hash::make(12345678),
        ]);
    }
}
