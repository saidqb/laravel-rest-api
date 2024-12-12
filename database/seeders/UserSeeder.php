<?php

namespace Database\Seeders;

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
        //
        \App\Models\User::create([
            'id' => 1,
            'name' => 'Superadmin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make("superadmin"),
            'role_id' => 1,
        ]);
        \App\Models\User::create([
            'id' => 10,
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make("user"),
            'role_id' => 1,
        ]);

        \App\Models\User::create([
            'id' => 11,
            'name' => 'Guest',
            'email' => 'guest@example.com',
            'password' => Hash::make("guest"),
            'role_id' => 1,
        ]);

        \App\Models\User::factory(10)->create();
    }
}
