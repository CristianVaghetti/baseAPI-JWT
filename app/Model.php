<?php

namespace App;

use App\Models\ILoggable;
use Illuminate\Foundation\Auth\User as Authenticatable;

abstract class Model extends Authenticatable implements ILoggable
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