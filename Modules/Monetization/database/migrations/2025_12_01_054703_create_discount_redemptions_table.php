<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('discount_redemptions')) {
            Schema::create('discount_redemptions', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('discount_code_id')->nullable()->constrained('discount_codes')->nullOnDelete();
                $table->foreignId('plan_price_override_id')->nullable()->constrained('plan_price_overrides')->nullOnDelete();
                $table->foreignId('ad_plan_purchase_id')->constrained('ad_plan_purchases')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->decimal('amount_before', 14, 2);
                $table->decimal('amount_after', 14, 2);
                $table->decimal('discount_amount', 14, 2);
                $table->timestamp('redeemed_at');
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->index(['discount_code_id', 'user_id']);
                $table->index(['plan_price_override_id', 'redeemed_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_redemptions');
    }
};
