<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('discount_codes')) {
            Schema::create('discount_codes', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
                $table->foreignId('plan_price_override_id')->constrained('plan_price_overrides')->cascadeOnDelete();
                $table->string('code')->unique();
                $table->string('description')->nullable();
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->unsignedInteger('usage_cap')->nullable();
                $table->unsignedInteger('usage_count')->default(0);
                $table->unsignedInteger('per_user_cap')->nullable();
                $table->boolean('is_stackable')->default(false);
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->index(['plan_id', 'plan_price_override_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
