<?php

namespace App\Observers;

class UserObserver extends BaseObserver
{
    protected string $screen = 'Usuários';

    /**
     * Mapping columns to NOT log
     *
     * @var array
     */
    protected array $fieldsExcept = [
        'avatar',
        'password',
        'remember_token',
    ];
}
