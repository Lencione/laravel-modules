<?php

namespace Lencione\LaravelModules;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Lencione\LaravelModules\Commands\MakeModule;
use Lencione\LaravelModules\Commands\ModuleAction;
use Lencione\LaravelModules\Commands\ModuleController;
use Lencione\LaravelModules\Commands\ModuleEvent;
use Lencione\LaravelModules\Commands\ModuleJob;
use Lencione\LaravelModules\Commands\ModuleListener;
use Lencione\LaravelModules\Commands\ModuleModel;
use Lencione\LaravelModules\Commands\ModuleRequests;
use Lencione\LaravelModules\Commands\ModuleResources;
use Lencione\LaravelModules\Commands\ModuleRoute;
use Lencione\LaravelModules\Commands\ModuleRules;
use Lencione\LaravelModules\Commands\ModuleService;

class LaravelModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/modules.php', 'modules');
    }

    public function boot(): void
    {
        $this->bootRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModule::class,
                ModuleAction::class,
                ModuleController::class,
                ModuleEvent::class,
                ModuleJob::class,
                ModuleListener::class,
                ModuleModel::class,
                ModuleRequests::class,
                ModuleResources::class,
                ModuleRoute::class,
                ModuleRules::class,
                ModuleService::class,
            ]);

            $this->publishes([
                __DIR__ . '/../stubs' => base_path('stubs'),
            ], 'laravel-modules-stubs');

            $this->publishes([
                __DIR__ . '/../config/modules.php' => config_path('modules.php'),
            ], 'laravel-modules-config');
        }
    }

    private function bootRoutes(): void
    {
        $modulesPath = config('modules.path') ?? app_path('Modules');

        if (! is_dir($modulesPath)) {
            return;
        }

        $webConfig = config('modules.routes.web', ['enabled' => true, 'middleware' => ['web'], 'prefix' => null]);
        $apiConfig = config('modules.routes.api', ['enabled' => true, 'middleware' => ['api'], 'prefix' => 'api']);

        foreach (scandir($modulesPath) as $module) {
            if ($module === '.' || $module === '..') {
                continue;
            }

            $modulePath = "{$modulesPath}/{$module}";
            if (! is_dir($modulePath)) {
                continue;
            }

            $this->registerRouteFile($modulePath, $module, 'web', $webConfig);
            $this->registerRouteFile($modulePath, $module, 'api', $apiConfig);
        }
    }

    /**
     * @param  array{enabled: bool, middleware: array<int, string>, prefix: ?string}  $config
     */
    private function registerRouteFile(string $modulePath, string $module, string $type, array $config): void
    {
        if (! ($config['enabled'] ?? true)) {
            return;
        }

        $file = "{$modulePath}/Routes/{$type}.php";
        if (! file_exists($file)) {
            return;
        }

        $route = Route::middleware($config['middleware'] ?? [$type]);

        $prefix = $config['prefix'] ?? null;
        if ($prefix) {
            $route = $route->prefix(str_replace('{module}', strtolower($module), $prefix));
        }

        $route->group($file);
    }
}
