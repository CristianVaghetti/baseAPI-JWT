<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    use SoftDeletes;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'logs';
    
    /**
     * Table attributes modifable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'screen',
        'action',
        'date_time_action',
        'model',
        'item_id'
    ];

    /**
     * Get the details that owns the Log
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(LogDetail::class);
    }

    /**
     * Relationship with user
     *
     * @return User
     */
    public function user()
    {
        return $this->belongsTo('App\User')->withTrashed();
    }
}
