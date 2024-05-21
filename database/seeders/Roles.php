<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

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
    }
}
