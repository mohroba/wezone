<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1) ads – Add advertisable_type_id and populate (safe)
        |--------------------------------------------------------------------------
        */
        Schema::table('ads', function (Blueprint $table) {
            if (!Schema::hasColumn('ads', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        $typeMap = DB::table('advertisable_types')->pluck('id', 'model_class');

        if (Schema::hasColumn('ads', 'advertisable_type') && $typeMap->isNotEmpty()) {
            DB::table('ads')
                ->whereNull('advertisable_type_id')
                ->orderBy('id')
                ->chunkById(500, function ($rows) use ($typeMap) {
                    foreach ($rows as $row) {
                        $typeId = $typeMap[$row->advertisable_type] ?? null;
                        if ($typeId) {
                            DB::table('ads')->where('id', $row->id)
                                ->update(['advertisable_type_id' => $typeId]);
                        }
                    }
                });
        }

        /*
        |--------------------------------------------------------------------------
        | 2) ad_attribute_groups – safe migration
        |--------------------------------------------------------------------------
        */

        // Step A: Add new FK column
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        // Step B: Migrate data only if old column exists
        if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
            foreach ($typeMap as $modelClass => $typeId) {
                DB::table('ad_attribute_groups')
                    ->where('advertisable_type', $modelClass)
                    ->update(['advertisable_type_id' => $typeId]);
            }
        }

        // Step C: Drop legacy columns & FKs safely
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                    $table->dropIndex('ad_attribute_groups_advertisable_type_category_id_index');
                }
                $table->dropConstrainedForeignId('category_id');
            }

            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }
        });

        /*
|--------------------------------------------------------------------------
| 3) ad_attribute_definitions – SAFE & IDEMPOTENT
|--------------------------------------------------------------------------
*/

// Step A: Drop old unique index if it exists
        if ($this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_group_id_key_unique');
            });
        }

// Step B: rename column only if needed
        if (Schema::hasColumn('ad_attribute_definitions', 'group_id')
            && !Schema::hasColumn('ad_attribute_definitions', 'attribute_group_id'))
        {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->renameColumn('group_id', 'attribute_group_id');
            });
        }

// Step C: Safely change data_type column
        if (Schema::hasColumn('ad_attribute_definitions', 'data_type')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->string('data_type', 32)->change();
            });
        }

// Step D: Add NEW unique index only if it does NOT exist
        if (!$this->indexExists(
            'ad_attribute_definitions',
            'ad_attribute_definitions_attribute_group_id_key_unique'
        )) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                if (Schema::hasColumn('ad_attribute_definitions', 'attribute_group_id')
                    && Schema::hasColumn('ad_attribute_definitions', 'key'))
                {
                    $table->unique(
                        ['attribute_group_id', 'key'],
                        'ad_attribute_definitions_attribute_group_id_key_unique'
                    );
                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | 4) ad_attribute_values – full safe migration
        |--------------------------------------------------------------------------
        */

        // Step A: Add ad_id if missing
        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->foreignId('ad_id')
                    ->nullable()
                    ->after('definition_id')
                    ->constrained('ads')
                    ->cascadeOnDelete();
            }
        });

        // Step B: Backfill ad_id
        if (
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

        // Step C: Drop blocking FK
        if ($this->foreignKeyExists('ad_attribute_values', 'ad_attribute_values_definition_id_foreign')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->dropForeign('ad_attribute_values_definition_id_foreign');
            });
        }

        // Step D: Drop unique, indexes, columns
        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_unique')) {
                $table->dropUnique('ad_attribute_values_unique');
            }

            if (Schema::hasColumn('ad_attribute_values', 'advertisable_type') &&
                Schema::hasColumn('ad_attribute_values', 'advertisable_id') &&
                $this->indexExists('ad_attribute_values', 'ad_attribute_values_advertisable_type_advertisable_id_index')
            ) {
                $table->dropIndex('ad_attribute_values_advertisable_type_advertisable_id_index');
            }

            if (Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }
            if (Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->dropColumn('advertisable_id');
            }

            if (!Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->date('value_date')->nullable()->after('value_boolean');
            }

            if ($this->columnsExist('ad_attribute_values', ['ad_id', 'definition_id'])) {
                $table->unique(['ad_id', 'definition_id'], 'ad_attribute_values_ad_definition_unique');
            }
        });

        // Step E: Re-add FK
        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if ($this->columnsExist('ad_attribute_values', ['definition_id'])) {
                $table->foreign('definition_id', 'ad_attribute_values_definition_id_foreign')
                    ->references('id')
                    ->on('ad_attribute_definitions')
                    ->cascadeOnDelete();
            }
        });

        /*
        |--------------------------------------------------------------------------
        | 5) ad_categories – safe
        |--------------------------------------------------------------------------
        */
        Schema::table('ad_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('parent_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }

            if (Schema::hasColumn('ad_categories', 'filters_schema')) {
                $table->dropColumn('filters_schema');
            }
            if (Schema::hasColumn('ad_categories', 'depth')) {
                $table->dropColumn('depth');
            }
            if (Schema::hasColumn('ad_categories', 'path')) {
                $table->dropColumn('path');
            }
        });

        if (Schema::hasColumn('ad_category_ad', 'ad_id')) {
            $categoryAssignments = DB::table('ad_category_ad')
                ->join('ads', 'ads.id', '=', 'ad_category_ad.ad_id')
                ->whereNotNull('ads.advertisable_type_id')
                ->select('ad_category_ad.category_id', DB::raw('MAX(ads.advertisable_type_id) as type_id'))
                ->groupBy('ad_category_ad.category_id')
                ->get();

            foreach ($categoryAssignments as $assignment) {
                DB::table('ad_categories')
                    ->whereNull('advertisable_type_id')
                    ->where('id', $assignment->category_id)
                    ->update(['advertisable_type_id' => $assignment->type_id]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 6) ad_category_closure – safe
        |--------------------------------------------------------------------------
        */
        Schema::table('ad_category_closure', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_category_closure', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('descendant_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        if (
            Schema::hasColumn('ad_category_closure', 'ancestor_id') &&
            Schema::hasColumn('ad_category_closure', 'descendant_id') &&
            Schema::hasColumn('ad_categories', 'advertisable_type_id')
        ) {
            $categoryTypeMap = DB::table('ad_categories')
                ->whereNotNull('advertisable_type_id')
                ->pluck('advertisable_type_id', 'id');

            DB::table('ad_category_closure')
                ->orderBy('ancestor_id')
                ->chunk(500, function ($rows) use ($categoryTypeMap) {
                    foreach ($rows as $row) {
                        $typeId = $categoryTypeMap[$row->descendant_id] ?? null;
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

    /*
    |--------------------------------------------------------------------------
    | DOWN (safe rollback)
    |--------------------------------------------------------------------------
    */
    public function down(): void
    {
        Schema::table('ad_category_closure', function (Blueprint $table) {
            if (Schema::hasColumn('ad_category_closure', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });

        Schema::table('ad_categories', function (Blueprint $table) {
            if (!Schema::hasColumn('ad_categories', 'filters_schema')) {
                $table->json('filters_schema')->nullable();
            }
            if (!Schema::hasColumn('ad_categories', 'depth')) {
                $table->unsignedTinyInteger('depth')->default(0);
            }
            if (!Schema::hasColumn('ad_categories', 'path')) {
                $table->string('path', 1024)->nullable();
            }
            if (Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_ad_definition_unique')) {
                $table->dropUnique('ad_attribute_values_ad_definition_unique');
            }

            if (Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->dropConstrainedForeignId('ad_id');
            }
            if (Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->dropColumn('value_date');
            }

            if (!Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->string('advertisable_type');
            }
            if (!Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->unsignedBigInteger('advertisable_id');
            }

            $table->unique(
                ['definition_id', 'advertisable_type', 'advertisable_id'],
                'ad_attribute_values_unique'
            );
        });

        if ($this->indexExists(
            'ad_attribute_definitions',
            'ad_attribute_definitions_attribute_group_id_key_unique'
        )) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_attribute_group_id_key_unique');
            });
        }

        Schema::table('ad_attribute_definitions', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_definitions', 'data_type')) {
                $table->string('data_type')->change();
            }
        });

        if (Schema::hasColumn('ad_attribute_definitions', 'attribute_group_id')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->renameColumn('attribute_group_id', 'group_id');
            });
        }

        Schema::table('ad_attribute_definitions', function (Blueprint $table) {
            $table->unique(['group_id', 'key'], 'ad_attribute_definitions_group_id_key_unique');
        });

        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
            if (!Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->string('advertisable_type')->nullable();
            }
            if (!Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->foreignId('category_id')
                    ->nullable()
                    ->constrained('ad_categories')
                    ->nullOnDelete();
            }
        });

        Schema::table('ads', function (Blueprint $table) {
            if (Schema::hasColumn('ads', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Helper methods
    |--------------------------------------------------------------------------
    */

    private function indexExists(string $table, string $name): bool
    {
        return !empty(DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$name]
        ));
    }

    private function foreignKeyExists(string $table, string $fk): bool
    {
        return !empty(DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_NAME = ?
             AND CONSTRAINT_NAME = ?
             AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$table, $fk]
        ));
    }

    private function columnsExist(string $table, array $columns): bool
    {
        foreach ($columns as $column) {
            if (!Schema::hasColumn($table, $column)) {
                return false;
            }
        }
        return true;
    }
};
