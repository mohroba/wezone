<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table): void {
            if (! Schema::hasColumn('ads', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        Schema::table('ad_categories', function (Blueprint $table): void {
            if (! Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('parent_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('ads', function (Blueprint $table): void {
            if (Schema::hasColumn('ads', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });

        Schema::table('ad_categories', function (Blueprint $table): void {
            if (Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });
    }
};
