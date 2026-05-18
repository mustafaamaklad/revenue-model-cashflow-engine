<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_construction_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                  ->unique()
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->decimal('total_construction_cost', 20, 4)->default(0);
            $table->decimal('total_other_capex',       20, 4)->default(0);

            $table->unsignedSmallInteger('construction_start_period')->default(1);
            $table->unsignedSmallInteger('construction_duration')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_construction_inputs');
    }
};