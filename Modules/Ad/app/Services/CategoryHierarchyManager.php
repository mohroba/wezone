<?php

namespace Modules\Ad\Services;

use Illuminate\Support\Collection;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdCategoryClosure;

class CategoryHierarchyManager
{
    /**
     * Apply closure records for a newly created category.
     */
    public function handleCreated(AdCategory $category): void
    {
        $category->refresh();
        $this->applyRecursively($category);
    }

    /**
     * Recalculate closure records for the provided category and its descendants.
     */
    public function rebuildSubtree(AdCategory $category): void
    {
        $category->refresh();
        $this->applyRecursively($category);
    }

    private function applyRecursively(AdCategory $category): void
    {
        $parent = $category->parent()->first();

        $this->syncClosureRecords($category, $parent);

        $children = $category->children()->orderBy('sort_order')->get();

        foreach ($children as $child) {
            $this->applyRecursively($child);
        }
    }

    private function syncClosureRecords(AdCategory $category, ?AdCategory $parent): void
    {
        AdCategoryClosure::where('descendant_id', $category->id)->delete();

        $ancestorRows = $parent
            ? AdCategoryClosure::where('descendant_id', $parent->id)->get(['ancestor_id', 'depth'])
            : new Collection();

        $records = $ancestorRows->map(function ($row) use ($category) {
            return [
                'ancestor_id' => $row->ancestor_id,
                'descendant_id' => $category->id,
                'depth' => $row->depth + 1,
                'advertisable_type_id' => $category->advertisable_type_id,
            ];
        })->all();

        $records[] = [
            'ancestor_id' => $category->id,
            'descendant_id' => $category->id,
            'depth' => 0,
            'advertisable_type_id' => $category->advertisable_type_id,
        ];

        AdCategoryClosure::insert($records);
    }
}
