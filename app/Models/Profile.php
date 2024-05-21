<?php

namespace App\Models;

use App\User;

class Profile extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'profiles';

    /**
     * Table attributes modifable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'roles',
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    protected $logAttributes = [
        'name' => 'Nome',
        'description' => 'Descrição',
        'status' => 'Situação',
        'roles' => 'Perfis',
    ];

    /**
     * Relationship with roles
     *
     * @return Role
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_profiles');
    }
}
