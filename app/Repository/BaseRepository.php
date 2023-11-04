<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Repository;

use App\Helpers\FileHelper;
use App\Repository\IRepository;
use App\Traits\MyDatabaseTransactions;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

abstract class BaseRepository implements IRepository
{
    use MyDatabaseTransactions;

    /**
     * Base model
     * 
     * @var \Illuminate\Database\Eloquent\Model 
     */
    protected $model;

    /**
     * Get the model by ID
     * 
     * @param int|string $id 
     * @return \Illuminate\Database\Eloquent\Model 
     */
    public function find(int | string $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Adds orderBy condition
     * 
     * @param QueryBuilder|EloquentBuilder|Expression $q 
     * @param array|\Closure                          $orderBy 
     * @return void 
     * 
     * @throws InvalidArgumentException 
     */
    protected function makeOrderBy(QueryBuilder | EloquentBuilder | Expression $q, array | \Closure $orderBy): void
    {
        if (\is_array($orderBy)) {
            $q->orderBy($orderBy[0], $orderBy[1]);
        } elseif (
            $orderBy instanceof \Closure 
            || $orderBy instanceof QueryBuilder 
            || $orderBy instanceof EloquentBuilder 
            || $orderBy instanceof Expression
        ) {
            $q->orderBy($orderBy);
        }
    }

    /**
     * Get the model by where clause
     * 
     * @param array         $where 
     * @param array\Closure $orderBy 
     * @return \Illuminate\Database\Eloquent\Collection  
     */
    public function findBy(array $where = [], array | \Closure $orderBy = []): Collection 
    {
        $q = $this->model->where($where);
        if($orderBy) {
            $this->makeOrderBy($q, $orderBy);
        }

        return $q->get();
    }

    /**
     * Get the model by where clause
     * 
     * @param array $where 
     * @return \Illuminate\Database\Eloquent\Model  
     */
    public function findByOne($where = []): ?Model
    {
        return $this->model->where($where)->first();
    }

    /**
     * Get all data
     * 
     * @param array|\Closure $orderBy 
     * @return \Illuminate\Database\Eloquent\Collection 
     */
    public function all(array | \Closure $orderBy = []): Collection 
    {
        $q = $this->model->query();
        if ($orderBy) {
            $this->makeOrderBy($q, $orderBy);
        }

        return $q->get();
    }

    /**
     * Get all data paginated
     * 
     * @param int            $limit 
     * @param int            $offset 
     * @param array|\Closure $orderBy
     * @return LengthAwarePaginator 
     */
    public function allPaginate(int $limit, int $offset, array|\Closure $orderBy = []): LengthAwarePaginator 
    {
        $q = $this->model->query();
        if ($orderBy) {
            $this->makeOrderBy($q, $orderBy);
        }

        return $q->paginate(perPage: $limit, page: $offset);
    }

    /**
     * Save model without auto commit
     * 
     * @param array $data 
     * @return \Illuminate\Database\Eloquent\Model|null  
     * 
     * @throws InvalidArgumentException 
     */
    protected function _save(array $data): ?Model
    {
        $model = $this->model->findOrNew($data['id'] ?? '0');
        $model->fill($data);

        return $model->save() ? $model : null;
    }

    /**
     * Save model with auto commit
     * 
     * @param array $data 
     * @return \Illuminate\Database\Eloquent\Model|null  
     * 
     * @throws BindingResolutionException 
     */
    private function _saveWithAutoCommit(array $data): ?Model
    {
        $saved = null;
        try {
            $this->beginTransaction();
            $saved = $this->_save($data);
            $this->commit();
        } catch (\Exception $ex) {
            report($ex);
            $this->rollback();

            throw new \Exception(message: "Failed to save data: {$ex->getMessage()}", previous: $ex);
        }
        return $saved;
    }

    /**
     * Save model
     * 
     * @param array $data 
     * @param bool  $autoCommit 
     * @return \Illuminate\Database\Eloquent\Model|null 
     * 
     * @throws MassAssignmentException 
     * @throws InvalidArgumentException 
     * @throws InvalidCastException 
     */
    public function save(array $data, bool $autoCommit = false): ?Model 
    {
        if ($autoCommit) {
            return $this->_saveWithAutoCommit($data);
        } else {
            return $this->_save($data);
        }
    }

    /**
     * Delete model by ID
     * 
     * @param int $id 
     * @return bool 
     */
    public function delete(int $id): bool
    {
        try {
            $result = false;
            $model = $this->model->find($id);
            if ($model) {
                $result = $model->delete();
            }
        } catch (\Exception $e) {
            throw new \Exception("Failed to delete record!", 500, $e);
        }

        return $result;
    }

    /**
     * Store file in local storage
     * 
     * @param string $path 
     * @param mixed $content 
     * @param string $ext 
     * @param string $prefix 
     * 
     * @return string 
     */
    public function storageFile(string $path, $content, string $ext, string $prefix = ''): string
    {
        $path = $path . '/' . $prefix . \md5(\rand(1, 1000)) . "." . $ext;
        Storage::disk('local')->put($path, \base64_decode(FileHelper::extractBase64($content)));

        return $path;
    }

    /**
     * Checks for the existence of a record based on some conditions.
     * 
     * @param array $data 
     * @param null|int $id 
     * @param null|bool $trashed 
     * @return bool 
     */
    public function exists(array $data, ?int $id = null, ?bool $trashed = null): bool
    {
        $q = $this->model->query();
        if ($trashed) {
            $q->withTrashed();
        }
        \collect($data)->each(function($value) use($q) {
            if (isset($value['raw']) && $value['raw']) {
                $q->where(DB::raw($value['field']), $value['operator'], $value['value']);
            } elseif (isset($value['has']) && $value['has']) {
                $this->_existsHas($q, $value);
            } elseif (isset($value['isNull']) || isset($value['isNotNull'])) {
                $this->_existsNull($q, $value);
            } else {
                $q->where($value['field'], $value['operator'], $value['value']);
            }
        });

        if ($id && $id > 0) {
            $q->where('id', \DIFF, $id);
        }

        return $q->exists();
    }

    /**
     * Apply conditions in a relationship.
     * 
     * @param Builder $query 
     * @param array $value 
     * @return void 
     */
    private function _existsHas(Builder $query, array $value)
    {
        $query->whereHas($value['relation'], function($qH) use($value) {
            $trashed = isset($value['trashed']) && $value['trashed'];
            \collect($value['whereHas'])->each(function($where) use($qH, $trashed) {
                if ($trashed) {
                    $qH->withTrashed();
                }

                if (!empty($where)) {
                    $qH->where(DB::raw($where['field']), $where['operator'], $where['value']);
                }
            });
        });
    }

    /**
     * Applies conditions to null and non-null fields
     * 
     * @param Builder $query 
     * @param array $value 
     * @return void 
     */
    private function _existsNull(Builder $query, array $value)
    {
        switch (true) {
            case isset($value['isNull']) && $value['isNull']:
                $query->whereNull($value['field']);
                break;

            case isset($value['isNotNull']) && $value['isNotNull']:
                $query->whereNotNull($value['field']);
                break;

            default:
                break;
        }
    }
}
