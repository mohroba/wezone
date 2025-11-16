<?php

namespace Modules\Ad\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Ad\Http\Requests\AdReport\HandleAdReportRequest;
use Modules\Ad\Http\Requests\AdReport\ListAdReportRequest;
use Modules\Ad\Http\Requests\AdReport\StoreAdReportRequest;
use Modules\Ad\Http\Requests\AdReport\UpdateAdReportRequest;
use Modules\Ad\Http\Resources\AdReportResource;
use Modules\Ad\Models\AdReport;

class AdReportController extends Controller
{
    private const EAGER_RELATIONS = ['ad', 'reporter', 'handler'];

    /**
     * List ad reports.
     *
     * @group Ads Review
     */
    public function index(ListAdReportRequest $request): AnonymousResourceCollection
    {
        $filters = $request->validated();

        $query = AdReport::query()
            ->with(self::EAGER_RELATIONS)
            ->when($filters['status'] ?? null, fn (Builder $builder, string $status) => $builder->where('status', $status))
            ->when($filters['ad_id'] ?? null, fn (Builder $builder, int $adId) => $builder->where('ad_id', $adId))
            ->when($filters['reported_by'] ?? null, fn (Builder $builder, int $reportedBy) => $builder->where('reported_by', $reportedBy))
            ->when($filters['handled_by'] ?? null, fn (Builder $builder, int $handledBy) => $builder->where('handled_by', $handledBy))
            ->when($filters['reason_code'] ?? null, fn (Builder $builder, string $reason) => $builder->where('reason_code', $reason))
            ->when($filters['from'] ?? null, fn (Builder $builder, string $from) => $builder->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn (Builder $builder, string $to) => $builder->whereDate('created_at', '<=', $to))
            ->when($filters['search'] ?? null, function (Builder $builder, string $search): void {
                $builder->where(function (Builder $subQuery) use ($search): void {
                    $likeSearch = '%' . $search . '%';
                    $subQuery->where('description', 'like', $likeSearch)
                        ->orWhere('resolution_notes', 'like', $likeSearch);
                });
            })
            ->orderByDesc('created_at');

        $perPage = (int) ($filters['per_page'] ?? 15);
        $perPage = max(1, min($perPage, 100));

        if ($request->boolean('without_pagination')) {
            return AdReportResource::collection($query->get());
        }

        return AdReportResource::collection(
            $query->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Create an ad report.
     *
     * @group Ads Review
     */
    public function store(StoreAdReportRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['reported_by'] = optional($request->user())->getKey();

        $report = AdReport::create($payload);
        $report->refresh()->load(self::EAGER_RELATIONS);

        return (new AdReportResource($report))->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * View an ad report.
     *
     * @group Ads Review
     */
    public function show(AdReport $adReport): AdReportResource
    {
        return new AdReportResource($adReport->load(self::EAGER_RELATIONS));
    }

    /**
     * Update an ad report.
     *
     * @group Ads Review
     */
    public function update(UpdateAdReportRequest $request, AdReport $adReport): AdReportResource
    {
        $report = $this->persistStatusUpdate($adReport, $request->validated(), $request->user());

        return new AdReportResource($report);
    }

    /**
     * Delete an ad report.
     *
     * @group Ads Review
     */
    public function destroy(AdReport $adReport): Response
    {
        $adReport->delete();

        return response()->noContent();
    }

    /**
     * Resolve an ad report.
     *
     * @group Ads Review
     */
    public function resolve(HandleAdReportRequest $request, AdReport $adReport): AdReportResource
    {
        $payload = array_merge($request->validated(), ['status' => 'resolved']);
        $report = $this->persistStatusUpdate($adReport, $payload, $request->user());

        return new AdReportResource($report);
    }

    /**
     * Dismiss an ad report.
     *
     * @group Ads Review
     */
    public function dismiss(HandleAdReportRequest $request, AdReport $adReport): AdReportResource
    {
        $payload = array_merge($request->validated(), ['status' => 'dismissed']);
        $report = $this->persistStatusUpdate($adReport, $payload, $request->user());

        return new AdReportResource($report);
    }

    private function persistStatusUpdate(AdReport $adReport, array $payload, ?User $actor): AdReport
    {
        if (array_key_exists('metadata', $payload)) {
            $adReport->metadata = $payload['metadata'];
        }

        if (array_key_exists('resolution_notes', $payload)) {
            $adReport->resolution_notes = $payload['resolution_notes'];
        }

        if (array_key_exists('status', $payload)) {
            $adReport->status = $payload['status'];

            if ($payload['status'] === 'pending') {
                $adReport->handled_by = null;
                $adReport->handled_at = null;
            } elseif ($actor) {
                $adReport->handled_by = $actor->getKey();
                $adReport->handled_at = now();
            }
        }

        $adReport->save();

        return $adReport->load(self::EAGER_RELATIONS);
    }
}
