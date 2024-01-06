<?php

namespace App\Http\Validation;

use App\Helpers\ResponseHelper;
use App\Http\Validation\IValidation;
use App\Repository\PasswordRepository;
use Illuminate\Support\Arr;

class PasswordValidation implements IValidation
{
    /**
     * Repository of Password
     *
     * @var PasswordRepository
     */
    protected PasswordRepository $repository;

    /**
     * Create a new validation instance.
     *
     * @param PasswordRepository $repository
     * @return void
     */
    public function __construct(PasswordRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Make a busines validate
     *
     * @param array $dados
     * @param int|null $id
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function validate(array $dados, $id = 0): array | JsonResponse
    {
        if (!$this->repository->exists(Arr::only($dados, ["email"]), $id)) {
            return ResponseHelper::responseValidateError([], msg: "Email não localizado!", json: false);
        }

        return ResponseHelper::responseSuccess(json: false);
    }

}