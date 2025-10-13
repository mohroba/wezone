<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('payable');
            $table->decimal('amount', 14, 2);
            $table->char('currency', 3)->default('IRR');
            $table->string('gateway');
            $table->string('status')->default('pending');
            $table->string('ref_id')->nullable();
            $table->string('tracking_code')->nullable();
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();
            $table->uuid('correlation_id')->nullable();
            $table->string('idempotency_key')->nullable();
            $table->timestamps();

            $table->unique('ref_id');
            $table->index(['payable_type', 'payable_id']);
            $table->index(['status', 'created_at']);
            $table->unique(['idempotency_key', 'gateway']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
