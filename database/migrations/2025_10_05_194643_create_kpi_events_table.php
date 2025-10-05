<?php

use App\Models\User;
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
        Schema::create('kpi_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('event_uuid')->nullable()->unique();
            $table->foreignId('kpi_device_id')->constrained('kpi_devices')->cascadeOnDelete();
            $table->foreignId('kpi_session_id')->nullable()->constrained('kpi_sessions')->nullOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('event_key');
            $table->string('event_name')->nullable();
            $table->string('event_category')->nullable();
            $table->double('event_value')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['kpi_device_id', 'occurred_at']);
            $table->index(['event_key', 'occurred_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_events');
    }
};
