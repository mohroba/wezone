<?php

namespace Tests\Feature\Ad;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Models\AdvertisableType;
use Modules\Ad\Services\CategoryHierarchyManager;
use Tests\TestCase;

class AdCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_category_and_builds_hierarchy(): void
    {
        $type = AdvertisableType::factory()->create();
        $parent = AdCategory::create([
            'slug' => 'vehicles',
            'name' => 'Vehicles',
            'advertisable_type_id' => $type->id,
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($parent);
        $parent->refresh();

        $response = $this->postJson('/api/ad-categories', [
            'advertisable_type_id' => $type->id,
            'parent_id' => $parent->id,
            'slug' => 'cars',
            'name' => 'Cars',
            'sort_order' => 5,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.slug', 'cars')
            ->assertJsonPath('data.parent_id', $parent->id)
            ->assertJsonPath('data.advertisable_type_id', $type->id);

        $this->assertDatabaseHas('ad_category_closure', [
            'ancestor_id' => $parent->id,
            'descendant_id' => $response->json('data.id'),
            'depth' => 1,
            'advertisable_type_id' => $type->id,
        ]);
    }

    public function test_it_updates_category_and_recalculates_closure(): void
    {
        $type = AdvertisableType::factory()->create();
        $root = AdCategory::create([
            'slug' => 'root',
            'name' => 'Root',
            'advertisable_type_id' => $type->id,
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($root);
        $root->refresh();

        $alternativeParent = AdCategory::create([
            'slug' => 'secondary',
            'name' => 'Secondary',
            'advertisable_type_id' => $type->id,
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($alternativeParent);
        $alternativeParent->refresh();

        $child = AdCategory::create([
            'parent_id' => $root->id,
            'slug' => 'child',
            'name' => 'Child',
            'advertisable_type_id' => $type->id,
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($child);
        $child->refresh();

        $response = $this->postJson("/api/ad-categories/{$child->id}/update", [
            'parent_id' => $alternativeParent->id,
            'slug' => 'child-updated',
            'name' => 'Child Updated',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.parent_id', $alternativeParent->id);

        $this->assertDatabaseHas('ad_category_closure', [
            'ancestor_id' => $alternativeParent->id,
            'descendant_id' => $child->id,
            'depth' => 1,
        ]);

        $this->assertDatabaseMissing('ad_category_closure', [
            'ancestor_id' => $root->id,
            'descendant_id' => $child->id,
            'depth' => 1,
        ]);
    }
}
