<?php

namespace App\Repository;

use App\Helpers\ResponseHelper;
use App\Repository\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class AuthRepository extends BaseRepository
{
    /**
     * Repository of users
     *
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * Create a new repository instance
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    
    /**
     * Authenticate user using mail and passsword
     *
     * @param array $data
     * @return array
     */
    public function authenticate(array $data = []) : array
    {
        $user = $this->userRepository->findByOne(Arr::only($data, ['email']));
        if (!$user) {
            return ResponseHelper::responseError(msg: 'Usuário não encontrado. Tente novamente!', json: false, statusCode: 403);
        }

        if ($user->status === '0') {
            return ResponseHelper::responseError(
                msg: 'Não foi possível realizar o login. Entre em contato com o administrador do sistema!',
                json: false,
                statusCode: 406
            );
        }

        $email = $data['email'];
        $password = $data['password'];
        
        // Generate user token
        $token = \auth()->attempt(\compact('email', 'password'));

        if (!$token) {
            return ResponseHelper::responseError(msg: 'Dados inválidos. Tente novamente!', json: false, statusCode: 403);
        }

        $expires_in = \auth()->{'factory'}()->getTTL() * 60;

        $data = [
            'token' => $token,
            'expires_in' => Carbon::now('America/Sao_Paulo')->addSeconds($expires_in)->format('Y-m-d H:i:s'),
            'user' => $user,
        ];

        return ResponseHelper::responseSuccess(data: $data, msg: 'Autenticado com sucesso!', json: false);
    }
}
