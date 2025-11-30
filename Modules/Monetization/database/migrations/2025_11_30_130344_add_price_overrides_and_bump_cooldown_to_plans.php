<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            if (! Schema::hasColumn('plans', 'price_overrides')) {
                $table->json('price_overrides')->nullable()->after('features');
            }

            if (! Schema::hasColumn('plans', 'bump_cooldown_minutes')) {
                $table->unsignedInteger('bump_cooldown_minutes')->nullable()->after('price_overrides');
            }
        });

        if (! Schema::hasTable('plan_price_overrides')) {
            Schema::create('plan_price_overrides', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();
                $table->foreignId('advertisable_type_id')->constrained('advertisable_types')->cascadeOnDelete();
                $table->foreignId('ad_category_id')->nullable()->constrained('ad_categories')->nullOnDelete();
                $table->decimal('override_price', 14, 2)->nullable();
                $table->char('currency', 3)->nullable();
                $table->enum('discount_type', ['none', 'percent', 'fixed'])->default('none');
                $table->decimal('discount_value', 14, 2)->nullable();
                $table->timestamp('discount_starts_at')->nullable();
                $table->timestamp('discount_ends_at')->nullable();
                $table->unsignedInteger('usage_cap')->nullable();
                $table->unsignedInteger('usage_count')->default(0);
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->unique([
                    'plan_id',
                    'advertisable_type_id',
                    'ad_category_id',
                ], 'plan_price_override_unique_key');
            });
        }
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table): void {
            if (Schema::hasColumn('plans', 'bump_cooldown_minutes')) {
                $table->dropColumn('bump_cooldown_minutes');
            }

            if (Schema::hasColumn('plans', 'price_overrides')) {
                $table->dropColumn('price_overrides');
            }
        });

        Schema::dropIfExists('plan_price_overrides');
    }
};
