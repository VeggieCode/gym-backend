<?php

namespace App\Providers;

use App\Domain\Repositories\AuthRepositoryInterface;
use App\Domain\Repositories\EntrenamientoRepositoryInterface;
use App\Domain\Repositories\PlanRepositoryInterface;
use App\Domain\Repositories\RutinaRepositoryInterface;
use App\Infrastructure\Repositories\EloquentAuthRepository;
use App\Infrastructure\Repositories\EloquentEntrenamientoRepository;
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

        $this->app->bind(AuthRepositoryInterface::class, EloquentAuthRepository::class);

        $this->app->bind(EntrenamientoRepositoryInterface::class, EloquentEntrenamientoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
