<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Profile;

class Profiles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profiles = [
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'Acessa total ao sistema.',
                'status' => true,
            ],
        ];

        foreach($profiles as $profile){
            Profile::create($profile);
        }
    }
}
