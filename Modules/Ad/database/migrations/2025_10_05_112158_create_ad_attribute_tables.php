<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_attribute_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('advertisable_type')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('ad_categories')->nullOnDelete();
            $table->unsignedSmallInteger('display_order')->default(0);
            $table->timestamps();

            $table->index(['advertisable_type', 'category_id']);
        });

        Schema::create('ad_attribute_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->nullable()->constrained('ad_attribute_groups')->nullOnDelete();
            $table->string('key');
            $table->string('label');
            $table->text('help_text')->nullable();
            $table->enum('data_type', ['string', 'integer', 'decimal', 'boolean', 'enum', 'json']);
            $table->string('unit')->nullable();
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_filterable')->default(false);
            $table->boolean('is_searchable')->default(false);
            $table->string('validation_rules')->nullable();
            $table->timestamps();

            $table->unique(['key', 'group_id']);
        });

        Schema::create('ad_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('definition_id')->constrained('ad_attribute_definitions')->cascadeOnDelete();
            $table->morphs('advertisable');
            $table->string('value_string')->nullable();
            $table->bigInteger('value_integer')->nullable();
            $table->decimal('value_decimal', 15, 4)->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->json('value_json')->nullable();
            $table->string('normalized_value')->nullable();
            $table->timestamps();

            $table->unique(['definition_id', 'advertisable_type', 'advertisable_id'], 'ad_attribute_values_unique');
            $table->index(['advertisable_type', 'advertisable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_attribute_values');
        Schema::dropIfExists('ad_attribute_definitions');
        Schema::dropIfExists('ad_attribute_groups');
    }
};
