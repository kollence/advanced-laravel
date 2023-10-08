<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'confirmed_email' => true,
        ]);
        \App\Models\User::factory()->create([
            'name' => 'User1',
            'email' => 'user1@example.com',
            'confirmed_email' => true,
        ]);
        \App\Models\User::factory()->create([
            'name' => 'User2',
            'email' => 'user2@example.com',
            'confirmed_email' => false,
        ]);
    }
}
