<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Adminstrator',
            'email' => 'eziz5505@gmail.com',
            'phone' => '+99362240774',
            'password' => Hash::make('password'), 
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Client User',
            'email' => 'christmas4017@gmail.com',
            'phone' => '+99362626262',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
