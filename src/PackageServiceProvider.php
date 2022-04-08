<?php

namespace Yago\Faq;

use App\Services\BlockService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Yago\Faq\Http\Controllers\FaqController;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(BlockService $blockService)
    {
        // Append config
        Config::set('lighthouse.namespaces.models', [
            'Yago\\Faq\\Models',
            ...Config::get('lighthouse.namespaces.models'),
        ]);

        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        $this->publishes([
            __DIR__ . '/../resources/backend/js/' => base_path('packages/backend/src/vendor/yago/faq/js'),
            __DIR__ . '/../resources/frontend/views/' => base_path('packages/frontend/src/views/vendor/yago-faq'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../resources/frontend/views', 'yago-faq');

        $blockService->module('faq-listing', [FaqController::class, 'listing']);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $dispatcher = app(\Illuminate\Contracts\Events\Dispatcher::class);
        $dispatcher->listen(
            \Nuwave\Lighthouse\Events\BuildSchemaString::class,
            function (): string {
                return file_get_contents(__DIR__ . '/../graphql/schema.graphql');
            }
        );
    }
}
