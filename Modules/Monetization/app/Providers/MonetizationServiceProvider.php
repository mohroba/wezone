<?php

namespace Modules\Monetization\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\Monetization\Domain\Entities\AdPlanPurchase;
use Modules\Monetization\Domain\Entities\Payment;
use Modules\Monetization\Domain\Entities\Plan;
use Modules\Monetization\Policies\PaymentPolicy;
use Modules\Monetization\Policies\PlanPolicy;
use Modules\Monetization\Policies\PurchasePolicy;
use Modules\Monetization\Domain\Contracts\Repositories\PaymentRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PlanRepository;
use Modules\Monetization\Domain\Contracts\Repositories\PurchaseRepository;
use Modules\Monetization\Domain\Contracts\Repositories\WalletRepository;
use Modules\Monetization\Domain\Repositories\EloquentPaymentRepository;
use Modules\Monetization\Domain\Repositories\EloquentPlanRepository;
use Modules\Monetization\Domain\Repositories\EloquentPurchaseRepository;
use Modules\Monetization\Domain\Repositories\EloquentWalletRepository;

class MonetizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(module_path('Monetization', 'config/config.php'), 'monetization');

        $this->app->bind(PlanRepository::class, EloquentPlanRepository::class);
        $this->app->bind(PurchaseRepository::class, EloquentPurchaseRepository::class);
        $this->app->bind(PaymentRepository::class, EloquentPaymentRepository::class);
        $this->app->bind(WalletRepository::class, EloquentWalletRepository::class);

        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(module_path('Monetization', 'database/migrations'));
        $this->registerPolicies();
    }

    private function registerPolicies(): void
    {
        Gate::policy(Plan::class, PlanPolicy::class);
        Gate::policy(AdPlanPurchase::class, PurchasePolicy::class);
        Gate::policy(Payment::class, PaymentPolicy::class);
    }
}
