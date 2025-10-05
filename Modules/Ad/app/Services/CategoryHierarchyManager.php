<?php

namespace Modules\Ad\Services;

use Illuminate\Support\Collection;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdCategoryClosure;

class CategoryHierarchyManager
{
    /**
     * Apply hierarchy metadata and closure records for a newly created category.
     */
    public function handleCreated(AdCategory $category): void
    {
        $category->refresh();
        $parent = $category->parent()->first();
        $this->applyRecursively($category, $parent);
    }

    /**
     * Recalculate hierarchy metadata for the provided category and its descendants.
     */
    public function rebuildSubtree(AdCategory $category): void
    {
        $category->refresh();
        $parent = $category->parent()->first();
        $this->applyRecursively($category, $parent);
    }

    private function applyRecursively(AdCategory $category, ?AdCategory $parent): void
    {
        $depth = $parent ? $parent->depth + 1 : 0;
        $path = $parent ? trim($parent->path . '>' . $category->slug, '>') : $category->slug;

        $category->forceFill([
            'depth' => $depth,
            'path' => $path,
        ])->save();

        AdCategoryClosure::where('descendant_id', $category->id)->delete();

        $ancestorRows = $parent
            ? AdCategoryClosure::where('descendant_id', $parent->id)->get(['ancestor_id', 'depth'])
            : new Collection();

        $records = $ancestorRows->map(function ($row) use ($category) {
            return [
                'ancestor_id' => $row->ancestor_id,
                'descendant_id' => $category->id,
                'depth' => $row->depth + 1,
            ];
        })->all();

        $records[] = [
            'ancestor_id' => $category->id,
            'descendant_id' => $category->id,
            'depth' => 0,
        ];

        AdCategoryClosure::insert($records);

        $children = $category->children()->orderBy('sort_order')->get();

        foreach ($children as $child) {
            $this->applyRecursively($child, $category);
        }
    }
}
