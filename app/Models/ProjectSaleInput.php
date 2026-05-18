<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSaleInput extends Model
{
    protected $fillable = [
        'project_id',
        'gdv',
        'sales_start_period',
        'sales_duration',
        'sales_cost_percentage',
    ];

    protected $casts = [
        'gdv'                   => 'decimal:4',
        'sales_start_period'    => 'integer',
        'sales_duration'        => 'integer',
        'sales_cost_percentage' => 'decimal:6',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function getSalesEndPeriodAttribute(): int
    {
        return $this->sales_start_period + $this->sales_duration - 1;
    }
}