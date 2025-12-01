<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $this->upgradeAttributeGroups();
        $this->upgradeDefinitions();
        $this->upgradeValues();
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            return;
        }

        $this->downgradeValues();
        $this->downgradeDefinitions();
        $this->downgradeGroups();
    }

    /*
    |--------------------------------------------------------------------------
    | UPGRADE GROUPS
    |--------------------------------------------------------------------------
    */
    private function upgradeAttributeGroups(): void
    {
        // 1) Add new FK column (NULLABLE)
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete(); // ensure SET NULL behavior
            }
        });

        // 2) Migrate old data only if old column exists
        $typeMap = DB::table('advertisable_types')->pluck('id', 'model_class');

        if (
            $typeMap->isNotEmpty() &&
            Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id') &&
            Schema::hasColumn('ad_attribute_groups', 'advertisable_type')
        ) {
            DB::table('ad_attribute_groups')
                ->whereNull('advertisable_type_id')
                ->orderBy('id')
                ->chunkById(500, function ($rows) use ($typeMap) {
                    foreach ($rows as $row) {
                        $mapped = $typeMap[$row->advertisable_type] ?? null;
                        if ($mapped) {
                            DB::table('ad_attribute_groups')
                                ->where('id', $row->id)
                                ->update(['advertisable_type_id' => $mapped]);
                        }
                    }
                });
        }

        // 3) Drop old index
        if ($this->indexExists('ad_attribute_groups', 'ad_attribute_groups_advertisable_type_category_id_index')) {
            Schema::table('ad_attribute_groups', function (Blueprint $table) {
                $table->dropIndex('ad_attribute_groups_advertisable_type_category_id_index');
            });
        }

        // 4) Drop old fields safely
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }
            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }
        });

        // ❌ DO NOT make advertisable_type_id NOT NULL — by your choice
    }

    /*
    |--------------------------------------------------------------------------
    | UPGRADE DEFINITIONS
    |--------------------------------------------------------------------------
    */
    private function upgradeDefinitions(): void
    {
        // Drop old unique if exists
        if ($this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_group_id_key_unique');
            });
        }

        // Rename column
        if (
            Schema::hasColumn('ad_attribute_definitions', 'group_id') &&
            !Schema::hasColumn('ad_attribute_definitions', 'attribute_group_id')
        ) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->renameColumn('group_id', 'attribute_group_id');
            });
        }

        // Add new index if missing
        if (! $this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_attribute_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->unique(['attribute_group_id', 'key'], 'ad_attribute_definitions_attribute_group_id_key_unique');
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPGRADE VALUES
    |--------------------------------------------------------------------------
    */
    private function upgradeValues(): void
    {
        // Add ad_id column
        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->foreignId('ad_id')
                    ->nullable()
                    ->after('definition_id')
                    ->constrained('ads')
                    ->cascadeOnDelete();
            }
        });

        // Fill ad_id from old polymorphic columns if they exist
        if (
            Schema::hasColumn('ad_attribute_values', 'ad_id') &&
            Schema::hasColumn('ad_attribute_values', 'advertisable_type') &&
            Schema::hasColumn('ad_attribute_values', 'advertisable_id')
        ) {
            DB::table('ad_attribute_values')
                ->whereNull('ad_id')
                ->orderBy('id')
                ->chunkById(500, function ($rows) {
                    foreach ($rows as $row) {
                        $adId = DB::table('ads')
                            ->where('advertisable_type', $row->advertisable_type)
                            ->where('advertisable_id', $row->advertisable_id)
                            ->value('id');

                        if ($adId) {
                            DB::table('ad_attribute_values')
                                ->where('id', $row->id)
                                ->update(['ad_id' => $adId]);
                        }
                    }
                });
        }

        // Drop old unique index
        if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_unique')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_values_unique');
            });
        }

        // Drop old index
        if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_advertisable_type_advertisable_id_index')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->dropIndex('ad_attribute_values_advertisable_type_advertisable_id_index');
            });
        }

        // Drop old polymorphic columns
        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }
            if (Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->dropColumn('advertisable_id');
            }

            if (!Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->date('value_date')->nullable();
            }
            if (!Schema::hasColumn('ad_attribute_values', 'value_json')) {
                $table->json('value_json')->nullable();
            }
            if (!Schema::hasColumn('ad_attribute_values', 'normalized_value')) {
                $table->string('normalized_value')->nullable();
            }
        });

        // New unique index
        if (! $this->indexExists('ad_attribute_values', 'ad_attribute_values_ad_definition_unique')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->unique(['ad_id', 'definition_id'], 'ad_attribute_values_ad_definition_unique');
            });
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DOWN (mirror rollback)
    |--------------------------------------------------------------------------
    */
    private function downgradeGroups(): void
    {
        // restore old columns
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->string('advertisable_type')->nullable();
            }
            if (! Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('ad_categories')->nullOnDelete();
            }
        });

        // restore index
        if (! $this->indexExists('ad_attribute_groups', 'ad_attribute_groups_advertisable_type_category_id_index')) {
            Schema::table('ad_attribute_groups', function (Blueprint $table) {
                $table->index(['advertisable_type', 'category_id'], 'ad_attribute_groups_advertisable_type_category_id_index');
            });
        }

        // drop new FK
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });
    }

    private function downgradeDefinitions(): void
    {
        if ($this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_attribute_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_attribute_group_id_key_unique');
            });
        }

        if (
            Schema::hasColumn('ad_attribute_definitions', 'attribute_group_id') &&
            !Schema::hasColumn('ad_attribute_definitions', 'group_id')
        ) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->renameColumn('attribute_group_id', 'group_id');
            });
        }

        if (! $this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->unique(['group_id', 'key'], 'ad_attribute_definitions_group_id_key_unique');
            });
        }
    }

    private function downgradeValues(): void
    {
        if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_ad_definition_unique')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_values_ad_definition_unique');
            });
        }

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->string('advertisable_type');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->unsignedBigInteger('advertisable_id');
            }
        });

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->dropConstrainedForeignId('ad_id');
            }

            if (Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->dropColumn('value_date');
            }

            if (! $this->indexExists('ad_attribute_values', 'ad_attribute_values_unique')) {
                $table->unique(['definition_id', 'advertisable_type', 'advertisable_id'], 'ad_attribute_values_unique');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    private function indexExists(string $table, string $indexName): bool
    {
        return !empty(DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$indexName]
        ));
    }
};
