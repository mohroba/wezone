<?php

namespace Tests\Feature\Ad;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ad\Models\AdCategory;
use Modules\Ad\Services\CategoryHierarchyManager;
use Tests\TestCase;

class AdCategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_lists_categories_with_filters(): void
    {
        $root = $this->createCategory('vehicles', 'وسایل نقلیه');
        $child = AdCategory::factory()->create([
            'parent_id' => $root->id,
            'slug' => 'cars',
            'name' => 'خودرو',
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($child);

        $response = $this->getJson('/api/ad-categories');
        $response->assertOk()
            ->assertJsonPath('meta.total', 2);

        $filtered = $this->getJson('/api/ad-categories?parent_id=' . $root->id);
        $filtered->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $child->id);
    }

    public function test_it_creates_a_category_and_builds_hierarchy(): void
    {
        $parent = $this->createCategory('digital-goods', 'کالاهای دیجیتال');

        $response = $this->postJson('/api/ad-categories', [
            'parent_id' => $parent->id,
            'slug' => 'mobile-phones',
            'name' => 'گوشی موبایل',
            'is_active' => true,
            'sort_order' => 5,
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.slug', 'mobile-phones')
            ->assertJsonPath('data.depth', $parent->depth + 1)
            ->assertJsonPath('data.path', $parent->path . '>mobile-phones');

        $this->assertDatabaseHas('ad_category_closure', [
            'ancestor_id' => $parent->id,
            'descendant_id' => $response->json('data.id'),
            'depth' => 1,
        ]);
    }

    public function test_it_shows_a_category(): void
    {
        $category = $this->createCategory('services', 'خدمات');

        $response = $this->getJson('/api/ad-categories/' . $category->id);

        $response->assertOk()
            ->assertJsonPath('data.id', $category->id)
            ->assertJsonPath('data.name', 'خدمات');
    }

    public function test_it_updates_category_and_recalculates_closure(): void
    {
        $root = $this->createCategory('root', 'ریشه');
        $alternativeParent = $this->createCategory('secondary', 'دسته دوم');

        $child = AdCategory::factory()->create([
            'parent_id' => $root->id,
            'slug' => 'child',
            'name' => 'زیرشاخه',
        ]);
        app(CategoryHierarchyManager::class)->handleCreated($child);

        $response = $this->postJson("/api/ad-categories/{$child->id}/update", [
            'parent_id' => $alternativeParent->id,
            'slug' => 'child-updated',
            'name' => 'زیرشاخه به‌روز',
            'is_active' => true,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.path', $alternativeParent->path . '>child-updated')
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

    public function test_it_soft_deletes_category(): void
    {
        $category = $this->createCategory('temporary', 'موقتی');

        $response = $this->postJson("/api/ad-categories/{$category->id}/delete");
        $response->assertNoContent();

        $this->assertDatabaseMissing('ad_categories', ['id' => $category->id]);
    }

    private function createCategory(string $slug, string $name): AdCategory
    {
        $category = AdCategory::factory()->create([
            'slug' => $slug,
            'name' => $name,
        ]);

        app(CategoryHierarchyManager::class)->handleCreated($category);

        return $category->fresh();
    }
}
