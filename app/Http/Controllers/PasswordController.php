<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Repository\PasswordRepository;
use App\Http\Validation\PasswordValidation;



class PasswordController extends Controller
{
    /**
     * Create a new controller instance
     * 
     * @param PasswordRepository $repository 
     * @return void 
     */
    public function __construct(
        PasswordRepository $repository, 
        PasswordValidation $validation
    )
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function forgot(Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->validation->validate($data);
            if ($response['success']) {
                if ($this->repository->forgot($data)) {
                    $response = ResponseHelper::responseSuccess([], "E-mail de atualização de senha enviado!");
                } else {
                    $response = ResponseHelper::responseError([], "Falha ao enviar email!");
                }
            } else {
                $response = response()->json($response, 422);
            }
        } catch (\Exception $ex) {
            $response = ResponseHelper::responseError([], $ex->getMessage());
        }

        return $response;
    }

    public function reset(Request $request)
    {
        try {
            $data = $request->all();
            if ($this->repository->reset($data)) {
                $response = ResponseHelper::responseSuccess([], "Alteração de senha bem-sucedida.");
            } else {
                $response = ResponseHelper::responseError([], "Falha ao atualizar a senha!");
            }
        } catch (\Exception $ex) {
            $response = ResponseHelper::responseError([], $ex->getMessage());
        }

        return $response;
    }
}
