<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $standardActions = ['list', 'read', 'create', 'update', 'delete'];

        $subjects = [
            'all' => ['manage'],
        ];

        foreach($subjects as $sub => $actions){
            foreach($actions as $act){
                Role::create([
                    'action' => $act,
                    'subject' => $sub,
                ]);
            }
        }

        DB::table('roles_profiles')->insert([
            'profile_id' => 1,
            'role_id' => 1,
        ]);
    }
}
