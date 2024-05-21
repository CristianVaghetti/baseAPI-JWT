<?php

namespace App\Observers;

use App\Models\Model;
use App\Model as Authenticatable;
use App\Repository\LogRepository;
use Illuminate\Support\Arr;

abstract class BaseObserver
{
    /**
     * Action screen 
     * 
     * @var string
     */
    protected string $screen = '';

    /**
     * Fields not to be considered
     * 
     * @var null|array
     */
    protected array $fieldsExcept = [];

    /**
     * Repository of audit
     * 
     * @var LogRepository
     */
    private LogRepository $repository;

    /**
     * Constructor
     * 
     * @param LogRepository $repository 
     * @return void 
     */
    public function __construct(LogRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\Model|Authenticatable  $model
     * @return void
     */
    public function created(Model | Authenticatable $model): void
    {
        $user = \auth()->user();
        $class = $model::class;
        $dateTime = \date('Y-m-d H:i:s');
        $attributes = Arr::except(
            $model->getAttributesWithoutCasting(), 
            \array_merge(['id', 'created_at', 'updated_at'], $this->fieldsExcept)
        );

        $logAttributes = $model->getLogAttributes();
        if (!empty($logAttributes) && !empty($this->screen) && !empty($attributes)) {
            $data = [
                'item_id' => $model->id,
                'user_id' => $user->id ?? 1,
                'screen' => $this->screen,
                'action' => 'criação',
                'date_time_action' => $dateTime,
                'model' => $class
            ];
            
            $log = $this->repository->save($data);
            foreach ($attributes as $key => $value) {
                $detail[] = [
                    'field' => $key,
                    'field_description' => $logAttributes[$key],
                    'old_value' => null,
                    'curr_value' => $value
                ];
            }
            $log->details()->createMany($detail);
        }
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\Model|Authenticatable  $model
     * @return void
     */
    public function updated(Model | Authenticatable  $model): void
    {
        $class = $model::class;
        $user = \auth()->user();
        $logAttributes = $model->getLogAttributes();
        $oldAttributes = Arr::except($model->getRawOriginal(), $this->fieldsExcept);
        
        $keys = \array_keys($model->getChanges());
        $attributes = Arr::except(
            Arr::only($model->getAttributesWithoutCasting(), $keys), 
            \array_merge(['id', 'created_at', 'updated_at', 'deleted_at'], $this->fieldsExcept)
        );

        if ($user && !empty($logAttributes) && !empty($this->screen) && !empty($attributes)) {
            $dateTime = \date('Y-m-d H:i:s');
            
            $data = [
                'item_id' => $model->id,
                'user_id' => $user->id,
                'screen' => $this->screen,
                'action' => 'alteração',
                'date_time_action' => $dateTime,
                'model' => $class
            ];

            $log = $this->repository->save($data);
            foreach ($attributes as $key => $value) {
                $detail[] = [
                    'field' => $key,
                    'field_description' => $logAttributes[$key],
                    'old_value' => $oldAttributes[$key] ?? null,
                    'curr_value' => $value ?? null,
                ];
            }
            $log->details()->createMany($detail);
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\Model|Authenticatable  $model
     * @return void
     */
    public function deleted(Model | Authenticatable  $model): void
    {
        $user = \auth()->user();
        $class = $model::class;
        $dateTime = \date('Y-m-d H:i:s');
        $oldAttributes = Arr::except(
            $model->getRawOriginal(), 
            \array_merge(['created_at', 'updated_at', 'deleted_at', 'id'], $this->fieldsExcept)
        );
        $logAttributes = $model->getLogAttributes();
        if (!empty($logAttributes) && !empty($this->screen)) {
            $data = [
                'item_id' => $model->id,
                'user_id' => $user->id,
                'screen' => $this->screen,
                'action' => 'remoção',
                'date_time_action' => $dateTime,
                'model' => $class
            ];

            $log = $this->repository->save($data);
            foreach ($oldAttributes as $key => $value) {
                $detail[] = [
                    'field' => $key,
                    'field_description' => $logAttributes[$key],
                    'old_value' => $value ?? null,
                    'curr_value' => null
                ];
            }
            $log->details()->createMany($detail);
        }
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\Model|Authenticatable  $model
     * @return void
     */
    public function restored(Model | Authenticatable  $model): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\Model|Authenticatable  $model
     * @return void
     */
    public function forceDeleted(Model | Authenticatable  $model): void
    {
        //
    }
}
