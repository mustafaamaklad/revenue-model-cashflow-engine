<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectScurveWeight extends Model
{
    protected $fillable = [
        'project_id',
        'construction_year',
        'raw_weight',
    ];

    protected $casts = [
        'construction_year' => 'integer',
        'raw_weight'        => 'decimal:6',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}