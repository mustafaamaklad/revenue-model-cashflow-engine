<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();

            $table->string('revenue_model');   // sale | rental | hybrid

            $table->unsignedSmallInteger('project_period');

            $table->timestamps();

            $table->index('revenue_model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};