<?php

namespace Modules\Ad\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Ad\Models\AdReport;

class AdReportPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('ad.report.manage');
    }

    public function view(User $user, AdReport $adReport): bool
    {
        return $user->can('ad.report.manage');
    }

    public function update(User $user, AdReport $adReport): bool
    {
        return $user->can('ad.report.manage');
    }

    public function delete(User $user, AdReport $adReport): bool
    {
        return $user->can('ad.report.manage');
    }
}
