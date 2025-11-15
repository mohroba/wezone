<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ads', function (Blueprint $table) {
            if (! Schema::hasColumn('ads', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        $typeMap = DB::table('advertisable_types')->pluck('id', 'model_class');

        if ($typeMap->isNotEmpty()) {
            DB::table('ads')
                ->whereNull('advertisable_type_id')
                ->whereIn('advertisable_type', $typeMap->keys())
                ->orderBy('id')
                ->chunkById(500, function ($ads) use ($typeMap): void {
                    foreach ($ads as $ad) {
                        $typeId = $typeMap->get($ad->advertisable_type);

                        if ($typeId) {
                            DB::table('ads')->where('id', $ad->id)->update(['advertisable_type_id' => $typeId]);
                        }
                    }
                });
        }

        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('advertisable_types')
                    ->nullOnDelete();
            }
        });

        foreach ($typeMap as $modelClass => $typeId) {
            DB::table('ad_attribute_groups')
                ->where('advertisable_type', $modelClass)
                ->update(['advertisable_type_id' => $typeId]);
        }

        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (
                Schema::hasColumn('ad_attribute_groups', 'advertisable_type') &&
                Schema::hasColumn('ad_attribute_groups', 'category_id')
            ) {
                $table->dropIndex('ad_attribute_groups_advertisable_type_category_id_index');
            }

            if (Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }

            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }
        });

        $legacyDefinitionIndexExists = $this->indexExists(
            'ad_attribute_definitions',
            'ad_attribute_definitions_group_id_key_unique'
        );

        if ($legacyDefinitionIndexExists) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_group_id_key_unique');
            });
        }

        if (Schema::hasColumn('ad_attribute_definitions', 'group_id')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->renameColumn('group_id', 'attribute_group_id');
            });
        }

        Schema::table('ad_attribute_definitions', function (Blueprint $table) {
            $table->string('data_type', 32)->change();
            $table->unique(['attribute_group_id', 'key'], 'ad_attribute_definitions_attribute_group_id_key_unique');
        });

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->foreignId('ad_id')
                    ->nullable()
                    ->after('definition_id')
                    ->constrained('ads')
                    ->cascadeOnDelete();
            }
        });

        DB::table('ad_attribute_values')
            ->whereNull('ad_id')
            ->orderBy('id')
            ->chunkById(500, function ($values): void {
                foreach ($values as $value) {
                    $adId = DB::table('ads')
                        ->where('advertisable_type', $value->advertisable_type)
                        ->where('advertisable_id', $value->advertisable_id)
                        ->value('id');

                    if ($adId) {
                        DB::table('ad_attribute_values')
                            ->where('id', $value->id)
                            ->update(['ad_id' => $adId]);
                    }
                }
            });

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            $table->dropUnique('ad_attribute_values_unique');

            if (
                Schema::hasColumn('ad_attribute_values', 'advertisable_type') &&
                Schema::hasColumn('ad_attribute_values', 'advertisable_id')
            ) {
                $table->dropIndex('ad_attribute_values_advertisable_type_advertisable_id_index');
            }

            if (Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }

            if (Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->dropColumn('advertisable_id');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->date('value_date')->nullable()->after('value_boolean');
            }

            $table->unique(['ad_id', 'definition_id'], 'ad_attribute_values_ad_definition_unique');
        });

        Schema::table('ad_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
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
                $table->json('filters_schema')->nullable();
            }

            if (! Schema::hasColumn('ad_categories', 'depth')) {
                $table->unsignedTinyInteger('depth')->default(0);
            }

            if (! Schema::hasColumn('ad_categories', 'path')) {
                $table->string('path', 1024)->nullable();
            }

            if (Schema::hasColumn('ad_categories', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            $table->dropUnique('ad_attribute_values_ad_definition_unique');

            if (Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->dropConstrainedForeignId('ad_id');
            }

            if (Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->dropColumn('value_date');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->string('advertisable_type');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->unsignedBigInteger('advertisable_id');
            }

            $table->unique(['definition_id', 'advertisable_type', 'advertisable_id'], 'ad_attribute_values_unique');
        });

        $newDefinitionIndexExists = $this->indexExists(
            'ad_attribute_definitions',
            'ad_attribute_definitions_attribute_group_id_key_unique'
        );

        if ($newDefinitionIndexExists) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_attribute_group_id_key_unique');
            });
        }

        Schema::table('ad_attribute_definitions', function (Blueprint $table) {
            $table->string('data_type')->change();
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

            if (! Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->string('advertisable_type')->nullable();
            }

            if (! Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('ad_categories')->nullOnDelete();
            }
        });

        Schema::table('ads', function (Blueprint $table) {
            if (Schema::hasColumn('ads', 'advertisable_type_id')) {
                $table->dropConstrainedForeignId('advertisable_type_id');
            }
        });
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();
        $tableName = $connection->getTablePrefix() . $table;

        return match ($driver) {
            'sqlite' => ! empty(DB::select(
                "SELECT name FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND name = ?",
                [$tableName, $indexName]
            )),
            'mysql' => ! empty(DB::select(
                sprintf('SHOW INDEX FROM `%s` WHERE Key_name = ?', $tableName),
                [$indexName]
            )),
            'pgsql' => ! empty(DB::select(
                'SELECT indexname FROM pg_indexes WHERE schemaname = current_schema() AND tablename = ? AND indexname = ?',
                [$tableName, $indexName]
            )),
            default => true,
        };
    }
};
