<?php

namespace App\Repository;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IRepository
{
    /**
     * Get the model by ID
     * 
     * @param int|string $id 
     * @return \Illuminate\Database\Eloquent\Model|null 
     */
    public function find(int | string $id): ?Model;

    /**
     * Get the collection models by where clause
     * 
     * @param array          $where 
     * @param array|\Closure $orderBy 
     * @return \Illuminate\Database\Eloquent\Collection  
     */
    public function findBy(array $where = [], array | \Closure $orderBy = []): Collection;

    /**
     * Get the model by where clause
     * 
     * @param array $where 
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByOne(array $where = []): ?Model;

    /**
     * Get all data
     * 
     * @param array|\Closure $orderBy 
     * @return \Illuminate\Database\Eloquent\Collection<int, static> 
     */
    public function all(array | \Closure $orderBy = []): Collection;

    /**
     * Get all data paginated
     * 
     * @param int            $limit 
     * @param int            $offset 
     * @param array|\Closure $orderBy 
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 
     */
    public function allPaginate(int $limit, int $offset, array | \Closure $orderBy = []): LengthAwarePaginator;

    /**
     * Save model
     * 
     * @param array $data 
     * @return \Illuminate\Database\Eloquent\Model|null 
     * 
     * @throws MassAssignmentException 
     * @throws InvalidArgumentException 
     * @throws InvalidCastException 
     */
    public function save(array $data): ?Model;

    /**
     * Delete model by ID
     * 
     * @param int $id 
     * @return bool 
     */
    public function delete(int $id): bool;

    /**
     * Checks for the existence of a record based on some conditions.
     * 
     * @param array $data 
     * @param null|int $id 
     * @return bool 
     */
    public function exists(array $data, ?int $id = null): bool;
}
