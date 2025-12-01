<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plan_price_overrides', function (Blueprint $table): void {
            if (! Schema::hasColumn('plan_price_overrides', 'is_stackable')) {
                $table->boolean('is_stackable')->default(false)->after('usage_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('plan_price_overrides', function (Blueprint $table): void {
            if (Schema::hasColumn('plan_price_overrides', 'is_stackable')) {
                $table->dropColumn('is_stackable');
            }
        });
    }
};
