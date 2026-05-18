<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_scurve_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->unsignedSmallInteger('construction_year');

            $table->decimal('raw_weight', 12, 6);

            $table->timestamps();

            $table->unique(['project_id', 'construction_year']);
            $table->index('project_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_scurve_weights');
    }
};