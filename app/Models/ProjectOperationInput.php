<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectOperationInput extends Model
{
    protected $fillable = [
        'project_id',
        'operation_start_period',
        'operation_duration',
        'nsa',
        'rent_per_area',
        'occupancy_rate',
        'rental_growth_rate',
        'exit_cap_rate',
    ];

    protected $casts = [
        'operation_start_period' => 'integer',
        'operation_duration'     => 'integer',
        'nsa'                    => 'decimal:4',
        'rent_per_area'          => 'decimal:4',
        'occupancy_rate'         => 'decimal:6',
        'rental_growth_rate'     => 'decimal:6',
        'exit_cap_rate'          => 'decimal:6',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getOperationEndPeriodAttribute(): int
    {
        return $this->operation_start_period + $this->operation_duration - 1;
    }
}