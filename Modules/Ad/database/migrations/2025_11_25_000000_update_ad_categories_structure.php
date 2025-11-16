<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ad_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('parent_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }

            foreach (['filters_schema', 'depth', 'path'] as $column) {
                if (Schema::hasColumn('ad_categories', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        // Backfill types for existing categories using their assigned ads.
        if (Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
            $categoryTypes = DB::table('ad_category_ad')
                ->join('ads', 'ads.id', '=', 'ad_category_ad.ad_id')
                ->whereNotNull('ads.advertisable_type_id')
                ->select('ad_category_ad.category_id', DB::raw('MAX(ads.advertisable_type_id) as type_id'))
                ->groupBy('ad_category_ad.category_id')
                ->pluck('type_id', 'category_id');

            foreach ($categoryTypes as $categoryId => $typeId) {
                DB::table('ad_categories')
                    ->where('id', $categoryId)
                    ->update(['advertisable_type_id' => $typeId]);
            }
        }

        Schema::table('ad_category_closure', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_category_closure', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('descendant_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        $categoryTypeMap = DB::table('ad_categories')
            ->whereNotNull('advertisable_type_id')
            ->pluck('advertisable_type_id', 'id');

        if ($categoryTypeMap->isNotEmpty()) {
            DB::table('ad_category_closure')
                ->orderBy('ancestor_id')
                ->chunk(500, function ($rows) use ($categoryTypeMap): void {
                    foreach ($rows as $row) {
                        $typeId = $categoryTypeMap->get($row->descendant_id);

                        if ($typeId) {
                            DB::table('ad_category_closure')
                                ->where('ancestor_id', $row->ancestor_id)
                                ->where('descendant_id', $row->descendant_id)
                                ->update(['advertisable_type_id' => $typeId]);
                        }
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::table('ad_category_closure', function (Blueprint $table) {
            if (Schema::hasColumn('ad_category_closure', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });

        Schema::table('ad_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_categories', 'filters_schema')) {
                $table->json('filters_schema')->nullable()->after('sort_order');
            }

            if (! Schema::hasColumn('ad_categories', 'depth')) {
                $table->unsignedTinyInteger('depth')->default(0)->after('parent_id');
            }

            if (! Schema::hasColumn('ad_categories', 'path')) {
                $table->string('path', 1024)->nullable()->after('depth');
            }

            if (Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });
    }
};
