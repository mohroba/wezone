<?php

namespace Modules\Monetization\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Domain\Services\PurchasePricingService;
use Modules\Monetization\Http\Requests\ValidateDiscountCodeRequest;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group Monetization
 *
 * Validate discount codes and contextual pricing for plans.
 */
class DiscountCodeController
{
    public function __construct(
        private readonly PlanRepository $planRepository,
        private readonly PurchasePricingService $pricingService,
    ) {
    }

    /**
     * Validate discount code
     *
     * @group Monetization
     *
     * Validate whether a discount code is applicable to the selected plan and context.
     *
     * @responseField data.list_price number Price before discount.
     * @responseField data.discounted_price number Price after applying discount.
     * @responseField data.currency string Currency for the calculated price.
     * @responseField data.price_rule_id integer|null Identifier of the matched price rule.
     * @responseField data.discount_code string|null Discount code that was validated.
     * @responseField data.discount_applied boolean Whether the discount code was applied.
     */
    public function validateCode(ValidateDiscountCodeRequest $request): JsonResponse
    {
        $plan = $request->filled('plan_slug')
            ? $this->planRepository->findBySlug($request->string('plan_slug')->toString())
            : ($request->filled('plan_id') ? $this->planRepository->findById($request->integer('plan_id')) : null);

        if (! $plan) {
            return new JsonResponse(['message' => 'Plan not found.'], Response::HTTP_NOT_FOUND);
        }

        $pricing = $this->pricingService->calculate(
            plan: $plan,
            advertisableTypeId: $request->integer('advertisable_type_id'),
            adCategoryId: $request->filled('ad_category_id') ? $request->integer('ad_category_id') : null,
            discountCode: $request->string('discount_code'),
            userId: $request->user()?->getKey(),
        );

        if (! $pricing->discountApplied) {
            return new JsonResponse(
                ['message' => 'Discount code is not applicable for this plan or context.'],
                Response::HTTP_UNPROCESSABLE_ENTITY,
            );
        }

        return new JsonResponse([
            'data' => [
                'list_price' => $pricing->listPrice,
                'discounted_price' => $pricing->discountedPrice,
                'currency' => $pricing->currency,
                'price_rule_id' => $pricing->priceRule?->getKey(),
                'discount_code' => $pricing->discountCode,
                'discount_applied' => $pricing->discountApplied,
            ],
        ]);
    }
}
