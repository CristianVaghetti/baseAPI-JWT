<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(Profiles::class);
        $this->call(Roles::class);

        \App\Models\User::factory()->create([
            'profile_id' => 1,
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'vaghetticristian@gmail.com',
        ]);
    }
}
