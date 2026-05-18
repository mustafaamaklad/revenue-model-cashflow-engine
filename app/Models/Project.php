<?php

namespace App\Models;

use App\Enums\RevenueModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'revenue_model',
        'project_period',
    ];

    protected $casts = [
        'revenue_model' => RevenueModel::class,
        'project_period' => 'integer',
    ];


    public function constructionInput(): HasOne
    {
        return $this->hasOne(ProjectConstructionInput::class);
    }

    public function scurveWeights(): HasMany
    {
        return $this->hasMany(ProjectScurveWeight::class)->orderBy('construction_year');
    }

    public function saleInput(): HasOne
    {
        return $this->hasOne(ProjectSaleInput::class);
    }

    public function operationInput(): HasOne
    {
        return $this->hasOne(ProjectOperationInput::class);
    }

    public function opexInput(): HasOne
    {
        return $this->hasOne(ProjectOpexInput::class);
    }

    public function calculationRuns(): HasMany
    {
        return $this->hasMany(CalculationRun::class)->latest();
    }

    public function latestCalculationRun(): HasOne
    {
        return $this->hasOne(CalculationRun::class)->latestOfMany();
    }
}