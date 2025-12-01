<?php

namespace Modules\Monetization\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Modules\Monetization\Domain\Contracts\Repositories\DiscountRedemptionRepository;
use Modules\Monetization\Http\Requests\RedemptionReportRequest;
use Modules\Monetization\Http\Resources\DiscountRedemptionResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Modules\Monetization\Domain\Entities\Plan;

class DiscountRedemptionReportController
{
    use AuthorizesRequests;

    public function __construct(private readonly DiscountRedemptionRepository $redemptionRepository)
    {
    }

    public function index(RedemptionReportRequest $request): AnonymousResourceCollection
    {
        $this->authorize('manage', new Plan());

        $filters = $request->only(['discount_code_id', 'price_rule_id', 'user_id', 'from', 'to']);

        return DiscountRedemptionResource::collection(
            $this->redemptionRepository->paginate($filters, $request->integer('per_page', 15)),
        );
    }
}
