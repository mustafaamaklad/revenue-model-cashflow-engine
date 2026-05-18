<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_opex_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                  ->unique()
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->decimal('property_management_percentage', 8, 6)->default(0);
            $table->decimal('insurance_percentage',           8, 6)->default(0);

            $table->decimal('maintenance_rate', 12, 4)->default(0);
            $table->decimal('utilities_rate',   12, 4)->default(0);

            $table->decimal('fixed_marketing_cost', 20, 4)->default(0);
            $table->decimal('fixed_staffing_cost',  20, 4)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_opex_inputs');
    }
};