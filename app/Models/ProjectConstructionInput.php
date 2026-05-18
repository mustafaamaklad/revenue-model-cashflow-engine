<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectConstructionInput extends Model
{
    protected $fillable = [
        'project_id',
        'total_construction_cost',
        'total_other_capex',
        'construction_start_period',
        'construction_duration',
    ];

    protected $casts = [
        'total_construction_cost'   => 'decimal:4',
        'total_other_capex'         => 'decimal:4',
        'construction_start_period' => 'integer',
        'construction_duration'     => 'integer',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getConstructionEndPeriodAttribute(): int
    {
        return $this->construction_start_period + $this->construction_duration - 1;
    }
}