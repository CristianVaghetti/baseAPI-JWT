<?php

namespace App\Repository;

use App\Models\Role;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;

class RoleRepository extends BaseRepository 
{
    /**
     * Model of role
     *
     * @var Role
     */
    protected $model;
    
    /**
     * Constructor
     *
     * @param Role $model
     */
    public function __construct(Role $model) 
    {
        $this->model = $model;
    } 
}
