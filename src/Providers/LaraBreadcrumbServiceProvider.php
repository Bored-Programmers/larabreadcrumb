<?php

namespace BoredProgrammers\LaraBreadcrumb\Providers;

use BoredProgrammers\LaraBreadcrumb\Service\BreadcrumbService;
use Illuminate\Support\ServiceProvider;

class LaraBreadcrumbServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->app->singleton(BreadcrumbService::class, function () {
            return BreadcrumbService::create();
        });

        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'larabreadcrumb');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/larabreadcrumb'),
        ], 'larabreadcrumb-views');
    }

    public function boot(): void
    {
    }

}
