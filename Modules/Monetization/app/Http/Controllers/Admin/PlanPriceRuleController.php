<?php

namespace Modules\Monetization\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Modules\Monetization\Domain\Contracts\Repositories\PlanPriceRuleRepository;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Http\Requests\PlanPriceRuleRequest;
use Modules\Monetization\Http\Resources\PlanPriceRuleResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class PlanPriceRuleController
{
    use AuthorizesRequests;

    public function __construct(private readonly PlanPriceRuleRepository $priceRuleRepository)
    {
    }

    public function index(Plan $plan): AnonymousResourceCollection
    {
        $this->authorize('manage', $plan);

        return PlanPriceRuleResource::collection(
            $this->priceRuleRepository->forPlan($plan, request()->integer('per_page', 15)),
        );
    }

    public function store(PlanPriceRuleRequest $request, Plan $plan): PlanPriceRuleResource
    {
        $this->authorize('manage', $plan);

        $priceRule = $this->priceRuleRepository->createForPlan($plan, $request->validated());

        return new PlanPriceRuleResource($priceRule->load('discountCodes'));
    }

    public function show(PlanPriceOverride $priceRule): PlanPriceRuleResource
    {
        $priceRule->loadMissing('plan');
        $this->authorize('manage', $priceRule->plan ?? new Plan());

        return new PlanPriceRuleResource($priceRule->load('discountCodes'));
    }

    public function update(PlanPriceRuleRequest $request, PlanPriceOverride $priceRule): PlanPriceRuleResource
    {
        $priceRule->loadMissing('plan');
        $this->authorize('manage', $priceRule->plan ?? new Plan());

        $updated = $this->priceRuleRepository->update($priceRule, $request->validated());

        return new PlanPriceRuleResource($updated->load('discountCodes'));
    }

    public function destroy(PlanPriceOverride $priceRule): JsonResponse
    {
        $priceRule->loadMissing('plan');
        $this->authorize('manage', $priceRule->plan ?? new Plan());

        $this->priceRuleRepository->delete($priceRule);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
