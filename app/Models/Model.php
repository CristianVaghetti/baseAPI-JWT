<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

abstract class Model extends EloquentModel implements ILoggable
{
    /**
     * Mapping columns to a user-friendly name to logging
     * 
     * @var array
     */
    protected $logAttributes = [];

    /** 
     * Get mapping columns to a user-friendly name to logging
     * 
     * @return array 
     */
    public function getLogAttributes(): array
    {
        return $this->logAttributes;
    }

    /** 
     * Get model attributes without triggering castings
     * 
     * @return null|array 
     */
    public function getAttributesWithoutCasting(): ?array
    {
        return $this->attributes;
    }
}