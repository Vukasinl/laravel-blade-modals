<?php

namespace Vukasinl\Modals;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\View\Component as IlluminateComponent;
use Vukasinl\Modals\Controllers\ModalAssetsController;

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
        $this->publishes([__DIR__ . '/../resources/css/modals.js' => resource_path('js/modals.js')], 'modals-js');
        $this->publishes([__DIR__ . '/../resources/css/modals.css' => resource_path('css/modals.css')], 'modals-css');

        $this->bootViews();
        $this->prefixComponents();
        $this->registerBladeDirectives();
        $this->registerRoutes();

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

    protected function registerRoutes()
    {
        RouteFacade::get('/modals/modals.js', [ModalAssetsController::class, 'script']);
        RouteFacade::get('/modals/modals.js.map', [ModalAssetsController::class, 'maps']);
        RouteFacade::get('/modals/modals.css', [ModalAssetsController::class, 'style']);
    }

    private function prefixComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = 'modals';
            // dd(config('modals.modals'));
            /** @var IlluminateComponent $component */
            foreach (config('modals.modals', []) as $alias => $component) {
                $blade->component($component, $alias, $prefix);
            }
        });
    }

    public function registerBladeDirectives()
    {
        Blade::directive('modalScript', function () {
            return $this->script();
        });

        Blade::directive('modalStyles', function () {
            return $this->style();
        });
    }

    // public function styles($options = [])
    // {
    //     $debug = config('app.debug');

    //     $styles = $this->cssAssets();

    //     // HTML Label.
    //     $html = $debug ? ['<!-- Livewire Styles -->'] : [];

    //     // CSS assets.
    //     $html[] = $debug ? $styles : $this->minify($styles);

    //     return implode("\n", $html);
    // }

    // public function scripts($options = [])
    // {
    //     $debug = config('app.debug');

    //     $scripts = $this->javaScriptAssets($options);

    //     // HTML Label.
    //     $html = $debug ? ['<!-- Livewire Scripts -->'] : [];

    //     // JavaScript assets.
    //     $html[] = $debug ? $scripts : $this->minify($scripts);

    //     return implode("\n", $html);
    // }

    protected function style()
    {
        $appUrl = config('modals.asset_url');

        $manifest = json_decode(file_get_contents(__DIR__ . '/../dist/manifest.json'), true);
        $versionedFileName = $manifest['/modals.css'];

        // Default to dynamic `livewire.js` (served by a Laravel route).
        $fullAssetPath = "{$appUrl}/modals{$versionedFileName}";

        // Use static assets if they have been published
        // if (file_exists(public_path('vendor/modals/modals.css'))) {
            // $publishedManifest = json_decode(file_get_contents(public_path('vendor/livewire/manifest.json')), true);
            // $versionedFileName = $publishedManifest['/livewire.js'];

            // $fullAssetPath = $appUrl . '/vendor/modals/modals.css';
        // }

        // Adding semicolons for this JavaScript is important,
        // because it will be minified in production.
        return <<<HTML
        <link rel="stylesheet" href="{$fullAssetPath}">
HTML;
    }

    protected function script()
    {
        $appUrl = config('modals.asset_url') ?: rtrim($options['asset_url'] ?? '', '/');

        $manifest = json_decode(file_get_contents(__DIR__ . '/../dist/manifest.json'), true);
        $versionedFileName = $manifest['/modals.js'];

        // Default to dynamic `livewire.js` (served by a Laravel route).
        // $fullAssetPath = "{$appUrl}/modals/modals.js";
        $fullAssetPath = "{$appUrl}/modals{$versionedFileName}";

        // Use static assets if they have been published
        // if (file_exists(public_path('vendor/modals/modals.js'))) {
            // $publishedManifest = json_decode(file_get_contents(public_path('vendor/livewire/manifest.json')), true);
            // $versionedFileName = $publishedManifest['/livewire.js'];

        //     $fullAssetPath = $appUrl . '/vendor/modals/modals.js';
        // }

        // Adding semicolons for this JavaScript is important,
        // because it will be minified in production.
        return <<<HTML
<script src="{$fullAssetPath}"></script>
HTML;
    }

}
