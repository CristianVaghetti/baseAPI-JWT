<?php

namespace App\Repository;

use App\Helpers\DateTimeHelper;
use App\Helpers\StringHelper;
use App\Repository\BaseRepository;
use App\Models\Log;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;

class LogRepository extends BaseRepository
{
    /**
     * Necessary Relationships
     */
    private const withRelations = ['user.individual','details'];

    /**
     * Filters
     * 
     * @var null|array
     */
    private ?array $filters = null;

    /**
     * Search
     * 
     * @var null|string
     */
    private ?string $search = null;

    /**
     * Create a new repository instance
     *
     * @param Log $log
     */
    public function __construct(Log $log)
    {
        $this->model = $log;
    }

    /**
     * Get all data
     * 
     * @param array $orderBy 
     * @return \Illuminate\Database\Eloquent\Collection 
     */
    public function all(array | \Closure $orderBy = []): Collection 
    {
        $q = $this->model
            ->query()
            ->select($this->_select())
            ->with(self::withRelations);
        $this->_filterByFilters($q, $this->filters);
        $this->_filterBySearch($q, $this->search);

        return $orderBy ? $q->orderBy($orderBy[0], $orderBy[1])->get() : $q->all();
    }

    /**
     * Get all data paginated
     * 
     * @param int $limit 
     * @param int $offset 
     * @return LengthAwarePaginator 
     */
    public function allPaginate(int $limit, int $offset, array|\Closure $orderBy = []): LengthAwarePaginator 
    {
        $q = $this->model
            ->query()
            ->select($this->_select())
            ->with(self::withRelations);
        $this->_filterByFilters($q, $this->filters);
        $this->_filterBySearch($q, $this->search);

        if ($orderBy) {
            $this->makeOrderBy($q, $orderBy);
        }

        return $q->paginate($limit, ['*'], 'page', $offset);
    }

    /** 
     * Gets the columns to display
     * 
     * @return array 
     */
    private function _select(): array
    {
        return [
            'id',
            'user_id',
            'item_id',
            'screen',
            'action',
            'date_time_action',
            'model',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * Get the model by ID
     * 
     * @param int|string $id 
     * @return \Illuminate\Database\Eloquent\Model 
     */
    public function find(int|string $id): ?Model
    {
        return $this->model
            ->select($this->_select())
            ->with(self::withRelations)
            ->find($id);
    }

    /**
     * Get all records according to filters
     * 
     * @param null|string $search 
     * @param null|array $filters 
     * @return Collection<array-key, EloquentBuilder> 
     * 
     * @throws InvalidFormatException 
     */
    public function filterBy(?string $search, ?array $filters): Collection
    {
        $q = $this->model
            ->query()
            ->select($this->_select())
            ->with(self::withRelations);
        $this->_filterByFilters($q, $filters);
        $this->_filterBySearch($q, $search);

        return $q->get();
    }

    /**
     * Apply the filters
     * 
     * @param Builder $q 
     * @param array $filters 
     * @return void 
     * 
     * @throws InvalidFormatException 
     */
    private function _filterByFilters(Builder $q, ?array $filters): void
    {
        if ($filters && !empty($filters)) {
            if (isset($filters['user_id']) && !empty($filters['user_id'])) {
                $q->where('user_id', $filters['user_id']);
            }

            if (isset($filters['screen']) && !empty($filters['screen'])) {
                $screen = StringHelper::toLower($filters['screen']);
                $q->whereRaw("screen LIKE ?", ['%' . $screen . '%']);
            }

            if (isset($filters['action']) && !empty($filters['action'])) {
                $screenAction = StringHelper::toLower($filters['action']);
                $q->whereRaw("action LIKE ?", ['%' . $screenAction . '%']);
            }

            if (isset($filters['date']) && !empty($filters['date'])) {
                $date = DateTimeHelper::dateToDb($filters['date'], 'Y-m-d');
                $q->whereRaw("(date_time_action >= ? and date_time_action <= ?)", ["{$date} 00:00:00", "{$date} 23:59:59"]);
            }
        }
    }

    /**
     * Apply search filter
     * 
     * @param Builder $q 
     * @param string $search 
     * @return void 
     */
    private function _filterBySearch(Builder $q, ?string $search): void
    {
        if ($search && !empty($search)) {
            $_search = StringHelper::toLowerAndRemoveAccentsSymbolsWhiteSpace($search);
            $q->where(function($qS) use($_search) {
                $qS->WhereHas('user', fn($qU) => 
                    $qU->whereRaw("name LIKE ?", ['%' . $_search . '%'])
                    ->orWhereRaw("F_REMOVE_ACENTOS(name) LIKE ?", ['%' . $_search . '%']))
                    ->whereRaw("screen LIKE ?", ['%' . $_search . '%'])
                    ->whereRaw("action LIKE ?", ['%' . $_search . '%'])
                ;
            });
        }
    }

    /**
     * Set filters
     *
     * @param null|array $value Filters
     *
     * @return self
     */ 
    public function setFilters($value): self
    {
        $this->filters = $value;
        return $this;
    }

    /**
     * Set search
     *
     * @param null|string $value Search
     *
     * @return self
     */ 
    public function setSearch($value): self
    {
        $this->search = $value;
        return $this;
    }
}
