<?php

namespace Modules\Monetization\Domain\Repositories;

use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Domain\Entities\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentPlanRepository implements PlanRepository
{
    public function listActive(): Collection
    {
        return Plan::query()
            ->where('active', true)
            ->orderBy('order_column')
            ->get();
    }

    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Plan::query()->orderBy('order_column')->paginate($perPage);
    }

    public function findById(int $id): ?Plan
    {
        return Plan::query()->find($id);
    }

    public function findBySlug(string $slug): ?Plan
    {
        return Plan::query()->where('slug', $slug)->first();
    }

    public function create(array $attributes): Plan
    {
        return Plan::query()->create($attributes);
    }

    public function update(Plan $plan, array $attributes): Plan
    {
        $plan->fill($attributes)->save();

        return $plan;
    }

    public function delete(Plan $plan): void
    {
        $plan->delete();
    }
}
