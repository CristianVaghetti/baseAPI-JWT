<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Validation\UserValidation;
use App\Repository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * Repository of user
     *
     * @var UserRepository
     */
    protected UserRepository $repository;

    /**
     * Validation of user
     *
     * @var UserValidation
     */
    protected UserValidation $validation;

    /**
     * Constructor
     *
     * @param UserRepository $repository
     * @param UserValidation $validation
     */
    public function __construct(UserRepository $repository, UserValidation $validation)
    {
        $this->repository = $repository;
        $this->validation = $validation;
    }

    public function index(Request $request)
    {
        try {
            $users = [];
            $filtros = $request->toArray() ?? [];
            
            if ($request->has('length')) {
                $users["users"] = $this->repository->search($filtros, $request->get('length'));
            } else {
                $users['users'] = $this->repository->search($filtros);
            }

            return ResponseHelper::responseSuccess(data: $users);
        } catch (\Exception $ex) {
            return ResponseHelper::responseError(msg: $ex->getMessage());
        }
    }

    /**
     * Storing a new user.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $response = $this->validation->validate($data);

            if ($response['success']) {
                if ($this->repository->save($data)) {
                    $response = ResponseHelper::responseSuccess(msg: "Usuário criado com sucesso.");
                } else {
                    $response = ResponseHelper::responseError(msg: "Falha ao criar o usuário!");
                }
            } else {
                $response = response()->json($response, 422);
            }
        } catch (\Exception $ex) {
            $response = ResponseHelper::responseError(msg: $ex->getMessage());
        }

        return $response;
    }

    /**
     * Updatind a user.
     */
    public function update(Request $request, int $id)
    {
        try {              
            $data = $request->all();
            $data['id'] = $id;
            
            $response = $this->validation->validate($data, $id);

            if ($response['success']) {
                if ($this->repository->save($data)) {
                    $response = ResponseHelper::responseSuccess(msg: "Usuário alterado com sucesso.");
                } else {
                    $response = ResponseHelper::responseError(msg: "Falha ao alterar os dados do usuário!");
                }
            } else {
                $response = ResponseHelper::responseError($response['data'], $response['msg']);
            }
        } catch (\Exception $ex) {
            $response = ResponseHelper::responseError(msg: $ex->getMessage());
        }

        return $response;
    }

    /**
     * Get user data for display.
     */
    public function show(int $id)
    {
        try {
            $user = $this->repository->find($id);

            if ($user) {
                return ResponseHelper::responseSuccess(data: $user->toArray());
            } else {
                return ResponseHelper::responseError(msg: "Usuário não encontrado!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError(msg: $ex->getMessage());
        }
    }

    /**
     * Delete a user.
     */
    public function destroy(int $id)
    {
        try {
            if ((int)\auth()->user()->id === $id) {
                return ResponseHelper::responseSuccess(msg: "Não é possível executar essa ação!", statusCode: 422);
            }
            
            $response = [];
            if ($this->repository->delete($id)) {
                $response = ResponseHelper::responseSuccess(msg: "Usuário deletado com sucesso.");
            } else {
                $response = ResponseHelper::responseError(msg: "Falha ao deletar o usuário!");
            }
        } catch (\Exception $ex) {
            $response = ResponseHelper::responseError(msg: $ex->getMessage());
        }

        return $response;
    }

    /**
     * Active a user.
     */
    public function active(int $id)
    {
        try {
            if ($this->repository->changeActive($id, true)) {
                return ResponseHelper::responseSuccess(msg: "O usuário foi ativado com sucesso.");
            } else {
                return ResponseHelper::responseError(msg: "Falha ao ativar o usuário!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError(msg: $ex->getMessage());
        }
    }

    /**
     * Inactivate the user.
     */
    public function inactive(int $id)
    {
        try {
            if ($this->repository->changeActive($id, false)) {
                return ResponseHelper::responseSuccess(msg: "O usuário foi desativado com sucesso.");
            } else {
                return ResponseHelper::responseError(msg: "Falha ao desativar o usuário!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError(msg: $ex->getMessage());
        }
    }
}
