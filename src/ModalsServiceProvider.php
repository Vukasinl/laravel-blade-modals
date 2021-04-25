<?php

namespace Vukasinl\Modals;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Component as IlluminateComponent;

class ModalsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-blade-modals');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-blade-modals');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([__DIR__ . '/../config/modals.php' => config_path('modals.php')], 'modals-config');
        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/modals')], 'modals-views');
        $this->publishes([__DIR__ . '/../resources/css/modals.css' => resource_path('css/modals.css')], 'modals-css');

        $this->bootViews();
        $this->prefixComponents();

        // if ($this->app->runningInConsole()) {

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-blade-modals'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-blade-modals'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-blade-modals'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        // }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/modals.php', 'modals');
    }

    protected function bootViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'modals');
        // Blade::component('modal::components.modal', 'modal');
    }

    private function prefixComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = 'modal';
            /** @var IlluminateComponent $component */
            foreach (config('modals.components', []) as $alias => $component) {
                $blade->component($component, $alias, $prefix);
            }
        });
    }
}
