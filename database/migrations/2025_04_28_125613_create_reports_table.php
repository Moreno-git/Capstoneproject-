<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('type'); // 'monthly', 'quarterly', 'annual', 'custom'
            $table->date('start_date');
            $table->date('end_date');
            $table->json('summary_data'); // Store summary statistics
            $table->json('chart_data'); // Store chart data
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->string('format')->nullable(); // excel, pdf
            $table->string('file_path')->nullable(); // Path to stored report file
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
