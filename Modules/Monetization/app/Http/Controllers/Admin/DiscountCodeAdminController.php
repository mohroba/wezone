<?php

namespace Modules\Monetization\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Monetization\Domain\Contracts\Repositories\DiscountCodeRepository;
use Modules\Monetization\Domain\Entities\DiscountCode;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Domain\Entities\PlanPriceOverride;
use Modules\Monetization\Http\Requests\DiscountCodeRequest;
use Modules\Monetization\Http\Resources\DiscountCodeResource;
use Symfony\Component\HttpFoundation\Response;

class DiscountCodeAdminController
{
    use AuthorizesRequests;

    public function __construct(private readonly DiscountCodeRepository $discountCodeRepository)
    {
    }

    public function index(PlanPriceOverride $priceRule): AnonymousResourceCollection
    {
        $priceRule->loadMissing('plan');
        $this->authorize('manage', $priceRule->plan ?? new Plan());

        return DiscountCodeResource::collection(
            $this->discountCodeRepository->forPriceRule($priceRule, request()->integer('per_page', 15)),
        );
    }

    public function store(DiscountCodeRequest $request, PlanPriceOverride $priceRule): DiscountCodeResource
    {
        $priceRule->loadMissing('plan');
        $this->authorize('manage', $priceRule->plan ?? new Plan());

        $discountCode = $this->discountCodeRepository->createForRule($priceRule, $request->validated());

        return new DiscountCodeResource($discountCode);
    }

    public function show(DiscountCode $discountCode): DiscountCodeResource
    {
        $discountCode->loadMissing('priceRule.plan');
        $this->authorize('manage', $discountCode->priceRule?->plan ?? new Plan());

        return new DiscountCodeResource($discountCode);
    }

    public function update(DiscountCodeRequest $request, DiscountCode $discountCode): DiscountCodeResource
    {
        $discountCode->loadMissing('priceRule.plan');
        $this->authorize('manage', $discountCode->priceRule?->plan ?? new Plan());

        $updated = $this->discountCodeRepository->update($discountCode, $request->validated());

        return new DiscountCodeResource($updated);
    }

    public function destroy(DiscountCode $discountCode): JsonResponse
    {
        $discountCode->loadMissing('priceRule.plan');
        $this->authorize('manage', $discountCode->priceRule?->plan ?? new Plan());

        $this->discountCodeRepository->delete($discountCode);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
