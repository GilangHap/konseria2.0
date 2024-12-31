<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Konseria',
            'email' => 'admin@konseria.com',
            'password' => Hash::make('password'), // atau gunakan password yang diinginkan
            'role' => 'admin', // atau role yang diinginkan
        ]);
    }
}
