<?php

namespace BoredProgrammers\LaraBreadcrumb\Providers;

use Illuminate\Support\ServiceProvider;

class LaraBreadcrumbServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'larabreadcrumb');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/larabreadcrumb'),
        ], 'larabreadcrumb-views');
    }

    public function boot(): void
    {
    }

}
