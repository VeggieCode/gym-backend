<?php

namespace App\Providers;

use App\Domain\Repositories\PlanRepositoryInterface;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Infrastructure\Repositories\EloquentPlanRepository;
use App\Infrastructure\Repositories\EloquentRutinaRepository;
use App\Infrastructure\Repositories\MockPlanRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            PlanRepositoryInterface::class,
            EloquentPlanRepository::class
        );

        $this->app->bind(
            RutinaRepositoryInterface::class,
            EloquentRutinaRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
