<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_period_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('calculation_run_id')
                  ->constrained('calculation_runs')
                  ->cascadeOnDelete();

            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->unsignedSmallInteger('period');  

            $table->decimal('construction_scurve_weight', 12, 8)->default(0);
            $table->decimal('construction_outflow',       20, 4)->default(0);
            $table->decimal('capex_weight',               12, 8)->default(0);
            $table->decimal('other_capex',                20, 4)->default(0);

            $table->decimal('sales_weight', 12, 8)->default(0);
            $table->decimal('sales_inflow', 20, 4)->default(0);
            $table->decimal('sales_cost',   20, 4)->default(0);

            $table->tinyInteger('operation_flag')->default(0); 
            $table->unsignedSmallInteger('operation_year_index')->default(0);
            $table->decimal('base_rental_income', 20, 4)->default(0);
            $table->decimal('rental_income',      20, 4)->default(0);

            $table->decimal('property_management_cost', 20, 4)->default(0);
            $table->decimal('insurance_cost',           20, 4)->default(0);
            $table->decimal('maintenance_cost',         20, 4)->default(0);
            $table->decimal('utilities_cost',           20, 4)->default(0);
            $table->decimal('marketing_cost',           20, 4)->default(0);
            $table->decimal('staffing_cost',            20, 4)->default(0);
            $table->decimal('opex',                     20, 4)->default(0);

            $table->decimal('noi',           20, 4)->default(0);
            $table->decimal('terminal_value', 20, 4)->default(0);

            $table->decimal('project_cash_flow', 20, 4)->default(0);

            $table->timestamps();

            $table->unique(['calculation_run_id', 'period']);
            
            $table->index(['project_id', 'calculation_run_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_period_results');
    }
};