<?php

namespace App\Models;

class Role extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Table attributes modifable
     *
     * @var array
     */
    protected $fillable = [
        'subject',
        'action',
    ];

    /**
     * Relationship with profiles
     *
     * @return Profile
     */
    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile', 'roles_profiles');
    }
}
