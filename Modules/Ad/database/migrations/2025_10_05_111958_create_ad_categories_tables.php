<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('ad_categories')->nullOnDelete();
            $table->unsignedTinyInteger('depth')->default(0);
            $table->string('path', 1024)->nullable();
            $table->string('slug')->unique();
            $table->string('name');
            $table->json('name_localized')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->json('filters_schema')->nullable();
            $table->timestamps();

            $table->index(['parent_id', 'sort_order']);
        });

        Schema::create('ad_category_closure', function (Blueprint $table) {
            $table->unsignedBigInteger('ancestor_id');
            $table->unsignedBigInteger('descendant_id');
            $table->unsignedTinyInteger('depth');

            $table->primary(['ancestor_id', 'descendant_id']);

            $table->foreign('ancestor_id')->references('id')->on('ad_categories')->cascadeOnDelete();
            $table->foreign('descendant_id')->references('id')->on('ad_categories')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_category_closure');
        Schema::dropIfExists('ad_categories');
    }
};
