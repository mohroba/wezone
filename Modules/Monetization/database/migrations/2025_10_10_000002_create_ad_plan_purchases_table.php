<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ad_plan_purchases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('ad_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 14, 2);
            $table->char('currency', 3)->default('IRR');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('payment_status')->default('draft');
            $table->string('payment_gateway')->nullable();
            $table->json('meta')->nullable();
            $table->uuid('correlation_id')->nullable()->unique();
            $table->string('idempotency_key')->nullable();
            $table->unsignedInteger('bump_allowance')->default(0);
            $table->timestamps();

            $table->index(['ad_id', 'ends_at']);
            $table->index(['user_id', 'created_at']);
            $table->unique(['idempotency_key', 'payment_gateway']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_plan_purchases');
    }
};
