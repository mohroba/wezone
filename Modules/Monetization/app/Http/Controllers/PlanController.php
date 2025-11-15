<?php

namespace Modules\Monetization\Http\Controllers;

use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Http\Resources\PlanResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Monetization
 *
 * Access available ad promotion plans and pricing information.
 */
class PlanController
{
    public function __construct(private readonly PlanRepository $planRepository)
    {
    }

    /**
     * List active plans
     *
     * @group Monetization
     *
     * Retrieve the list of plans that can currently be purchased. Plans marked as inactive are excluded.
     *
     * @responseField data[].id integer Unique identifier of the plan.
     * @responseField data[].name string Display name for the plan.
     * @responseField data[].price number Cost of the plan in the configured currency.
     */
    public function index(): AnonymousResourceCollection
    {
        return PlanResource::collection($this->planRepository->listActive());
    }
}
