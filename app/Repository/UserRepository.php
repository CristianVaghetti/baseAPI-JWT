<?php

namespace App\Repository;

use App\Helpers\FileHelper;
use App\Mail\GeneratedPassword;
use App\Repository\BaseRepository;
use App\Models\User;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Mime\Exception\LogicException;

class UserRepository extends BaseRepository
{
    /**
     * Constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Search users
     *
     * @param array $params
     * @param integer $limit
     * @return array
     */
    public function search(array $params = [], int $limit = null) : array
    {
        $model = $this->model;

        // Filter by status
        if (isset($params['status']) && $params['status']) {
            $model = $model->where('status', $params['status']);
        }

        $model->orderBy('name', 'asc');
        
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
     * Save model
     * 
     * @param array $data 
     * @return \Illuminate\Database\Eloquent\Model 
     * 
     * @throws MassAssignmentException 
     * @throws InvalidArgumentException 
     * @throws InvalidCastException 
     */
    public function save(array $data, bool $autoCommit = false): ?User
    {
        $model = null;
        try {
            $this->beginTransaction();

            if (!isset($data['id']) || empty($data['id'])) {
                $password = \Illuminate\Support\Str::random(8);
                $data = \array_merge($data, ['password' => bcrypt($password)]);
                $token = \Illuminate\Support\Str::random(128);

                $model = parent::save(data: $data);
                
                $expired_at = (new Carbon())->addHours(48);
                $newToken = $model->tokens()->create(\compact('token', 'expired_at'));

                Mail::to($model->email)->send(new GeneratedPassword($newToken, $model));
            } else {
                $model = parent::save(data: $data);
            }

            $this->commit();
        } catch (\Exception $e) {
            report($e);
            $this->rollback();
            throw new \Exception($e, 500, $e);
        }

        return $model;
    }

    /**
     * Checks if there is already a registered user.
     * 
     * @param array $data 
     * @param int|null $data 
     * @return bool 
     */
    public function exists(array $data, ?int $id = null, ?bool $trashed = null): bool
    {
        $q = $this->model->query();
        if ($trashed) {
            $q->withTrashed();
        }
        $q->where($data);
        if ($id && $id > 0) {
            $q->where('id', '<>', $id);
        }
        return $q->exists();
    }

    /**
     * Checks if the user has already made the first password change
     * 
     * @param int $id 
     * @return bool 
     * 
     * @throws InvalidArgumentException 
     * @throws RuntimeException 
     */
    public function hasChangedPassword(int $id): bool
    {
        return $this->model
            ->query()
            ->where(\compact('id'))
            ->has('passwords')->exists();
    }

    /**
     * Get only users who didn't change their password on first login
     * @param array $ids
     * 
     * @return Collection 
     * @throws RuntimeException 
     */
    public function getOnlyWithoutHasntChangedPassword(array $ids): Collection
    {
        return $this->model
            ->query()
            ->doesntHave('passwords')
            ->whereIn('id', $ids)
            ->get(['id', 'name', 'email']);
    }

    /**
     * Change user's status
     *
     * @param integer $id
     * @param boolean $status
     * @return boolean
     */
    public function changeStatus(int $id, bool $status) : bool
    {
        return $this->model->find($id)->fill([
            'status' => $status
        ])->save();
    }
}
