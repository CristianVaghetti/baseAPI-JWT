<?php

namespace App\Observers;

class ProfileObserver extends BaseObserver
{
    protected string $screen = 'Perfis de usuário';

    /**
     * Mapping columns to NOT log
     *
     * @var array
     */
    protected array $fieldsExcept = [];
}
