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
        Schema::create('kpi_uninstallations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kpi_device_id')->constrained('kpi_devices')->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->timestamp('uninstalled_at')->index();
            $table->string('app_version')->nullable();
            $table->string('reason')->nullable();
            $table->string('report_source')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['kpi_device_id', 'uninstalled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_uninstallations');
    }
};
