<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_operation_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                  ->unique()
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->unsignedSmallInteger('operation_start_period')->default(1);
            $table->unsignedSmallInteger('operation_duration')->default(1);

            $table->decimal('nsa', 16, 4)->default(0);

            $table->decimal('rent_per_area', 16, 4)->default(0);

            $table->decimal('occupancy_rate', 8, 6)->default(1.000000);

            $table->decimal('rental_growth_rate', 8, 6)->default(0.000000);

            $table->decimal('exit_cap_rate', 8, 6)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_operation_inputs');
    }
};