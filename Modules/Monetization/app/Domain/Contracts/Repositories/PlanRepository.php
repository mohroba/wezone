<?php

namespace Modules\Monetization\Domain\Contracts\Repositories;

use Modules\Monetization\Domain\Entities\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PlanRepository
{
    public function listActive(): Collection;

    public function paginate(int $perPage = 15): LengthAwarePaginator;

    public function findById(int $id): ?Plan;

    public function findBySlug(string $slug): ?Plan;

    public function create(array $attributes): Plan;

    public function update(Plan $plan, array $attributes): Plan;

    public function delete(Plan $plan): void;
}
