<?php

namespace App\Repository;

use App\User;
use App\Models\Token;

use App\Repository\BaseRepository;
use App\Mail\ForgotPassword;
use App\Mail\ResetPassword;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use App\Traits\MyDatabaseTransactions;

class PasswordRepository extends BaseRepository
{
    use MyDatabaseTransactions;

    protected User $user;
    protected Token $token;

    /**
     * Create a new repository instance
     * 
     * @param User $user
     * @param Token $token
     * @return void 
     */
    public function __construct(User $user, Token $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Change user password
     * 
     * @param array $data 
     * @return void 
     * 
     * @throws Exception 
     */
    public function reset(array $data)
    {
        $result = true;
        try {
            $this->beginTransaction();

            $token = $this->token->where(Arr::only($data, 'token'))->first();

            $user = $token->user;

            $password = Hash::make($data['newPassword']);
            $user->fill(\compact('password'))->save();
            $user->passwords()->create(\compact('password'));
            $token->delete();
            
            $this->commit();
        } catch (\Exception $e) {
            report($e);
            $this->rollback();

            $result = false;
            throw new \Exception("Falha a trocar a senha!", 500, $e);
        }

        return $result;
    }

    /**
     * Creates a new token to change the password and sends an email to the user
     * 
     * @param array $data 
     * @return bool 
     * 
     * @throws \Exception 
     */
    public function forgot(array $data)
    {
        $result = true;
        try {
            $this->beginTransaction();

            $user = $this->user->where(Arr::only($data, 'email'))->first();
            $token = \Illuminate\Support\Str::random(128);

            $expired_at = (new Carbon())->addHours(48);
            $newToken = $user->tokens()->create(\compact('token', 'expired_at'));

            Mail::to($user->email)->send(new ForgotPassword($user, $newToken));
            
            $this->commit();
        } catch (\Exception $e) {
            report($e);
            $this->rollback();

            $result = false;
            throw new \Exception("Falha ao gerar token de reset de senha!", 500, $e);
        }

        return $result;
    }

    /**
     * Change user password
     * 
     * @param array $data 
     * @param int|null $id 
     * @return void 
     * 
     * @throws Exception 
     */
    public function change(array $data, ?int $id)
    {
        $result = true;
        try {
            $this->beginTransaction();

            $user = $this->user->find($id);

            $password = Hash::make($data['new_password']);
            $user->fill(\compact('password'))->save();
            $user->passwords()->create(\compact('password'));
            
            $this->commit();

            Mail::to($user->email)->send(new ResetPassword($user));
        } catch (\Exception $e) {
            report($e);
            $this->rollback();

            $result = false;
            throw new \Exception("Falha ao trocar a senha!", 500, $e);
        }

        return $result;
    }

    public function exists(array $data, ?int $id = null, ?bool $trashed = null): bool
    {
        $q = $this->user->query();

        if ($trashed) {
            $q->withTrashed();
        }
        $q->where($data);
        
        return $q->exists();
    }
}