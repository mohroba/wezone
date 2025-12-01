<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ad_plan_purchases', function (Blueprint $table): void {
            if (! Schema::hasColumn('ad_plan_purchases', 'list_price')) {
                $table->decimal('list_price', 14, 2)->nullable()->after('amount');
            }

            if (! Schema::hasColumn('ad_plan_purchases', 'discounted_price')) {
                $table->decimal('discounted_price', 14, 2)->nullable()->after('list_price');
            }

            if (! Schema::hasColumn('ad_plan_purchases', 'price_rule_id')) {
                $table->foreignId('price_rule_id')->nullable()->after('plan_id')
                    ->constrained('plan_price_overrides')->nullOnDelete();
            }

            if (! Schema::hasColumn('ad_plan_purchases', 'discount_code')) {
                $table->string('discount_code')->nullable()->after('idempotency_key');
            }

            if (! Schema::hasColumn('ad_plan_purchases', 'bump_cooldown_minutes')) {
                $table->unsignedInteger('bump_cooldown_minutes')->nullable()->after('bump_allowance');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ad_plan_purchases', function (Blueprint $table): void {
            if (Schema::hasColumn('ad_plan_purchases', 'bump_cooldown_minutes')) {
                $table->dropColumn('bump_cooldown_minutes');
            }

            if (Schema::hasColumn('ad_plan_purchases', 'discount_code')) {
                $table->dropColumn('discount_code');
            }

            if (Schema::hasColumn('ad_plan_purchases', 'price_rule_id')) {
                $table->dropConstrainedForeignId('price_rule_id');
            }

            if (Schema::hasColumn('ad_plan_purchases', 'discounted_price')) {
                $table->dropColumn('discounted_price');
            }

            if (Schema::hasColumn('ad_plan_purchases', 'list_price')) {
                $table->dropColumn('list_price');
            }
        });
    }
};
