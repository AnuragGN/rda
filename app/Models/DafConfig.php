<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DafConfig extends Model
{
    // Table name
    protected $table = 'daf_config';

    // Primary key
    protected $primaryKey = 'id';

    // Fillable fields
    protected $fillable = [
        'sponsor_id',
        'config',
    ];

    // Automatically cast JSON to array
    protected $casts = [
        'config' => 'array',
    ];

    // Timestamps (created_at, updated_at)
    public $timestamps = true;

    /**
     * Get a specific step from config
     */
    public function getStep($key)
    {
        return $this->config[$key] ?? null;
    }

    /**
     * Check if a step is enabled
     */
    public function isStepEnabled($key)
    {
        return !empty($this->config[$key]['enabled']);
    }
}
