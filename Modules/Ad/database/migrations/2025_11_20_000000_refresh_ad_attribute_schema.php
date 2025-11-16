<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->upgradeAttributeGroups();
        $this->upgradeDefinitions();
        $this->upgradeValues();
    }

    public function down(): void
    {
        $this->downgradeValues();
        $this->downgradeDefinitions();
        $this->downgradeGroups();
    }

    private function upgradeAttributeGroups(): void
    {
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->foreignId('advertisable_type_id')
                    ->after('id')
                    ->constrained('advertisable_types')
                    ->cascadeOnDelete();
            }
        });

        $typeMap = DB::table('advertisable_types')->pluck('id', 'model_class');

        if (
            $typeMap->isNotEmpty() &&
            Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id') &&
            Schema::hasColumn('ad_attribute_groups', 'advertisable_type')
        ) {
            DB::table('ad_attribute_groups')
                ->orderBy('id')
                ->whereNull('advertisable_type_id')
                ->chunkById(500, function ($groups) use ($typeMap): void {
                    foreach ($groups as $group) {
                        $typeId = $typeMap->get($group->advertisable_type);

                        if ($typeId) {
                            DB::table('ad_attribute_groups')
                                ->where('id', $group->id)
                                ->update(['advertisable_type_id' => $typeId]);
                        }
                    }
                });
        }

        if ($this->indexExists('ad_attribute_groups', 'ad_attribute_groups_advertisable_type_category_id_index')) {
            Schema::table('ad_attribute_groups', function (Blueprint $table) {
                $table->dropIndex('ad_attribute_groups_advertisable_type_category_id_index');
            });
        }

        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->dropConstrainedForeignId('category_id');
            }

            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }
        });

        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_groups', 'advertisable_type_id')) {
                $table->unsignedBigInteger('advertisable_type_id')->nullable(false)->change();
            }
        });
    }

    private function upgradeDefinitions(): void
    {
        if ($this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_definitions_group_id_key_unique');
            });
        }

        if (
            Schema::hasColumn('ad_attribute_definitions', 'group_id') &&
            ! Schema::hasColumn('ad_attribute_definitions', 'attribute_group_id')
        ) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->renameColumn('group_id', 'attribute_group_id');
            });
        }

        if (! $this->indexExists('ad_attribute_definitions', 'ad_attribute_definitions_attribute_group_id_key_unique')) {
            Schema::table('ad_attribute_definitions', function (Blueprint $table) {
                $table->unique(['attribute_group_id', 'key'], 'ad_attribute_definitions_attribute_group_id_key_unique');
            });
        }
    }

    private function upgradeValues(): void
    {
        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->foreignId('ad_id')
                    ->nullable()
                    ->after('definition_id')
                    ->constrained('ads')
                    ->cascadeOnDelete();
            }
        });

        if (
            Schema::hasColumn('ad_attribute_values', 'ad_id') &&
            Schema::hasColumn('ad_attribute_values', 'advertisable_type') &&
            Schema::hasColumn('ad_attribute_values', 'advertisable_id')
        ) {
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
        }

        if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_unique')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->dropUnique('ad_attribute_values_unique');
            });
        }

        if ($this->indexExists('ad_attribute_values', 'ad_attribute_values_advertisable_type_advertisable_id_index')) {
            Schema::table('ad_attribute_values', function (Blueprint $table) {
                $table->dropIndex('ad_attribute_values_advertisable_type_advertisable_id_index');
            });
        }

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_values', 'advertisable_type')) {
                $table->dropColumn('advertisable_type');
            }

            if (Schema::hasColumn('ad_attribute_values', 'advertisable_id')) {
                $table->dropColumn('advertisable_id');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'value_date')) {
                $table->date('value_date')->nullable()->after('value_boolean');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'value_json')) {
                $table->json('value_json')->nullable()->after('value_date');
            }

            if (! Schema::hasColumn('ad_attribute_values', 'normalized_value')) {
                $table->string('normalized_value')->nullable()->after('value_json');
            }
        });

        Schema::table('ad_attribute_values', function (Blueprint $table) {
            if (Schema::hasColumn('ad_attribute_values', 'ad_id')) {
                $table->unsignedBigInteger('ad_id')->nullable(false)->change();
            }

            if (! $this->indexExists('ad_attribute_values', 'ad_attribute_values_ad_definition_unique')) {
                $table->unique(['ad_id', 'definition_id'], 'ad_attribute_values_ad_definition_unique');
            }
        });
    }

    private function downgradeGroups(): void
    {
        Schema::table('ad_attribute_groups', function (Blueprint $table) {
            if (! Schema::hasColumn('ad_attribute_groups', 'advertisable_type')) {
                $table->string('advertisable_type')->nullable()->after('advertisable_type_id');
            }

            if (! Schema::hasColumn('ad_attribute_groups', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('ad_categories')->nullOnDelete();
            }
        });

        if ($this->indexExists('ad_attribute_groups', 'ad_attribute_groups_advertisable_type_category_id_index') === false) {
            Schema::table('ad_attribute_groups', function (Blueprint $table) {
                $table->index(['advertisable_type', 'category_id'], 'ad_attribute_groups_advertisable_type_category_id_index');
            });
        }

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
            ! Schema::hasColumn('ad_attribute_definitions', 'group_id')
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
