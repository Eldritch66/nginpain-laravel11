<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Pak Kobul',
            'email' => 'kobul@gmail.com',
            'password' => Hash::make('qwerty12345'),
            'role' => 'pemilik',
        ]);

        User::create([
            'name' => 'Budi Pemilik',
            'email' => 'pemilik@test.com',
            'password' => Hash::make('password'),
            'role' => 'pemilik',
        ]);

        User::create([
            'name' => 'Ani Penyewa',
            'email' => 'penyewa@test.com',
            'password' => Hash::make('password'),
            'role' => 'penyewa',
        ]);
    }
}
