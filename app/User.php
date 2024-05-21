<?php

namespace App;

use App\Casts\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject
{
    use Notifiable, SoftDeletes, HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'profile_id',
        'avatar',
        'name',
        'username',
        'phone',
        'email',
        'password',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Mapping columns to a user-friendly name
     *
     * @var array
     */
    protected $logAttributes = [
        'profile_id' => 'Perfil',
        'avatar' => 'Foto de perfil',
        'name' => 'Nome',
        'username' => 'Usuário',
        'phone' => 'Telefone',
        'email' => 'Email',
        'status' => 'Situação',
    ];

    /**
     * Get all of the tokens for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany('App\Models\Token', 'user_id', 'id');
    }

    /**
     * Get all of the passwords for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passwords()
    {
        return $this->hasMany('App\Models\Passwords', 'user_id', 'id');
    }

    public function profiles()
    {
        return $this->belongsToMany('App\Models\Profile');
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
