<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ad_plan_purchases', function (Blueprint $table): void {
            if (! Schema::hasColumn('ad_plan_purchases', 'discount_code_id')) {
                $table->foreignId('discount_code_id')
                    ->nullable()
                    ->after('price_rule_id')
                    ->constrained('discount_codes')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ad_plan_purchases', function (Blueprint $table): void {
            if (Schema::hasColumn('ad_plan_purchases', 'discount_code_id')) {
                $table->dropConstrainedForeignId('discount_code_id');
            }
        });
    }
};
