<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        \App\Models\UserRole::insert([
            [
                'id' => 1,
                'name' => 'Super Admin',
                'permissions' => json_encode([]),
                'menus' => json_encode([]),
            ],
            [
                'id' => 2,
                'name' => 'user',
                'permissions' => json_encode([]),
                'menus' => json_encode([]),
            ]
        ]);
    }
}
