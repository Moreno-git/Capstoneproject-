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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type')->default('event');
            $table->enum('status', ['active', 'paused'])->default('active');
            $table->decimal('goal_amount', 10, 2)->nullable();
            $table->decimal('funds_raised', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->foreignId('category_id')->nullable()->constrained();
            $table->decimal('pledged_amount', 10, 2)->default(0);
            $table->integer('pledged_quantity')->default(0);
            $table->string('color')->nullable();
            $table->decimal('total_donations', 10, 2)->default(0);
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_archived')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
