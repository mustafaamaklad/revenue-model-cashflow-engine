<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calculation_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->string('triggered_by');  

            $table->string('revenue_model');
            $table->unsignedSmallInteger('project_period');

            $table->timestamps();

            $table->index(['project_id', 'created_at']);
            $table->index('triggered_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calculation_runs');
    }
};