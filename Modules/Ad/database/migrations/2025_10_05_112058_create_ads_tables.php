<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('advertisable');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', [
                'draft',
                'pending_review',
                'published',
                'rejected',
                'archived',
                'expired',
            ])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->bigInteger('price_amount')->nullable();
            $table->string('price_currency', 3)->nullable();
            $table->boolean('is_negotiable')->default(false);
            $table->boolean('is_exchangeable')->default(false);
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->json('contact_channel')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('share_count')->default(0);
            $table->unsignedBigInteger('favorite_count')->default(0);
            $table->timestamp('featured_until')->nullable();
            $table->unsignedDecimal('priority_score', 8, 4)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'published_at']);
            $table->index(['city_id', 'province_id']);
            $table->index(['featured_until', 'priority_score']);
        });

        Schema::create('ad_category_ad', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('ad_categories')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['ad_id', 'category_id']);
        });

        Schema::create('ad_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->enum('from_status', [
                'draft',
                'pending_review',
                'published',
                'rejected',
                'archived',
                'expired',
            ])->nullable();
            $table->enum('to_status', [
                'draft',
                'pending_review',
                'published',
                'rejected',
                'archived',
                'expired',
            ]);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['ad_id', 'created_at']);
        });

        Schema::create('ad_slug_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->string('slug');
            $table->string('redirect_to_slug')->nullable();
            $table->timestamps();

            $table->unique(['ad_id', 'slug']);
        });

        Schema::create('ad_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ad_id', 'user_id']);
        });

        Schema::create('ad_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ad_id')->constrained('ads')->cascadeOnDelete();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason_code');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_review', 'resolved', 'dismissed'])->default('pending');
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['status', 'handled_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_reports');
        Schema::dropIfExists('ad_favorites');
        Schema::dropIfExists('ad_slug_histories');
        Schema::dropIfExists('ad_status_histories');
        Schema::dropIfExists('ad_category_ad');
        Schema::dropIfExists('ads');
    }
};
