<?php

namespace App\Models;

interface ILoggable
{
    /** 
     * Get model attributes without triggering castings
     * 
     * @return null|array 
     */
    public function getAttributesWithoutCasting(): ?array;
}