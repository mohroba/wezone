<?php

namespace Modules\Monetization\Http\Controllers;

use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Http\Resources\PlanResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PlanController
{
    public function __construct(private readonly PlanRepository $planRepository)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        return PlanResource::collection($this->planRepository->listActive());
    }
}
