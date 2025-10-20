<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insert([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '123123123',
            'password' => Hash::make('123123123'),
            'type' => 1,
            'image' => 'default.png',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
