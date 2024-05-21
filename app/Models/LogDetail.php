<?php

namespace App\Models;

class LogDetail extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'logs_details';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Table attributes modifable
     *
     * @var array
     */
    protected $fillable = [
        'log_id',
        'field',
        'field_description',
        'old_value',
        'curr_value'
    ];

    /**
     * Relationship with Log
     *
     * @return Log
     */
    public function log()
    {
        return $this->belongsTo(Log::class);
    }
}
