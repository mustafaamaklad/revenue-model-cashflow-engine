<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectOpexInput extends Model
{
    protected $fillable = [
        'project_id',
        'property_management_percentage',
        'insurance_percentage',
        'maintenance_rate',
        'utilities_rate',
        'fixed_marketing_cost',
        'fixed_staffing_cost',
    ];

    protected $casts = [
        'property_management_percentage' => 'decimal:6',
        'insurance_percentage'           => 'decimal:6',
        'maintenance_rate'               => 'decimal:4',
        'utilities_rate'                 => 'decimal:4',
        'fixed_marketing_cost'           => 'decimal:4',
        'fixed_staffing_cost'            => 'decimal:4',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}