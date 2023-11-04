<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\AuthRequest;
use App\Repository\AuthRepository;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Repository of auth
     *
     * @var AuthRepository
     */
    protected $repository;

    /**
     * Create a new controller instance
     *
     * @param AuthRepository $repository
     */
    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function authenticate(AuthRequest $request)
    {
        try {
            $auth = $this->repository->authenticate($request->all());
            if ($auth['success']) {
                return ResponseHelper::responseSuccess(data: $auth['data'], msg: $auth['msg']);
            } else {
                return ResponseHelper::responseError(data: $auth['data'], msg: $auth['msg'], statusCode: $auth['status']);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError(msg: 'Falha na autenticação!');
        }
    }

    public function refresh()
    {
        try {
            $token = \auth()->{'refresh'}();
            $expires_in = \now()->addHours(1);
            return \response()->json(\compact('token', 'expires_in'));
        } catch (JWTException $e) {
            \report($e);
            return \response()->json(['error' => 'token_invalid'], 401);
        }
    }

    public function logout()
    {
        try {
            \auth()->logout();
            return ResponseHelper::responseSuccess(msg: 'Logout efetuado com sucesso');
        } catch (JWTException $e) {
            \report($e);
            return ResponseHelper::responseError(msg: 'Falha ao efetuar o logout');
        }
    }
}
