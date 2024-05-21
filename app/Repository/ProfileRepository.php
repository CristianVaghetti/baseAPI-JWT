<?php

namespace App\Repository;

use App\Models\Profile;
use App\Models\Role;
use App\Models\Legacy\Area;
use App\Repository\BaseRepository;
use Illuminate\Support\Facades\DB;

class ProfileRepository extends BaseRepository 
{
    /**
     * Model of profile
     *
     * @var Profile
     */
    protected $model;
    
    /**
     * Model of role
     *
     * @var Role
     */
    protected $role;

    /**
     * Model of area
     *
     * @var Area
     */
    protected $area;

    /**
     * Constructor
     *
     * @param Profile $model
     */
    public function __construct(Profile $model, Role $role) 
    {
        $this->model = $model;
        $this->role = $role;
    } 

    /**
     * Search profiles
     *
     * @param string $filter
     * @param array $params
     * @param integer $limit
     * @return array
     */
    public function search(string $filter, array $params = [], int $limit = null) : array
    {
        $model = $this->model->with(['roles', 'type']);

        // Filter by name
        if ($filter) {
            $model = $model->where('name', 'like', '%'.$filter.'%');
        }

        // Filter by status
        if (isset($params['status']) && $params['status']) {
            $model = $model->where('status', $params['status']);
        }
        $model->orderby('name', 'asc');

        if ($limit) {
            $paginator = $model->paginate($limit);
            return [
                'total' => $paginator->total(),
                'items' => $paginator->items()
            ];
        } else {
            return $model->get()->toArray();
        }
    }

    /**
     * Get the model by ID
     * 
     * @param int|string $id 
     * @return \Illuminate\Database\Eloquent\Model 
     */
    public function find(int | string $id): ?Profile
    {
        return $this->model->with(['roles'])->find($id);
    }

    
    /**
     * Insert or update plan
     *
     * @param array $data
     * @return ?Profile
     */
    public function save(array $data, bool $autoCommit = false): ?Profile
    {
        $model = null;
        try {
            $this->beginTransaction();

            $roles = $data['roles'];
            $data['roles'] = json_encode($data['roles']);

            $model = parent::save(data: $data);

            if($model && (isset($roles) && !empty($roles))){
                $model->roles()->sync($roles);
            }

            $this->commit();
        } catch (\Exception $e) {
            report($e);
            $this->rollback();
            throw new \Exception($e, 500, $e);
        }

        return $model;
    }
}
