<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_sale_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                  ->unique()
                  ->constrained('projects')
                  ->cascadeOnDelete();

            $table->decimal('gdv', 20, 4)->default(0);

            $table->unsignedSmallInteger('sales_start_period')->default(1);
            $table->unsignedSmallInteger('sales_duration')->default(1);

            $table->decimal('sales_cost_percentage', 8, 6)->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_sale_inputs');
    }
};