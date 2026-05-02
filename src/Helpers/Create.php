<?php

namespace Lencione\LaravelModules\Helpers;

use Illuminate\Support\Facades\File;

class Create
{
    public static function moduleFromStub(string $folder, string $stub, string $module, string $target): bool
    {
        $modulePath = self::modulePath($module);

        if (! is_dir($modulePath)) {
            return false;
        }

        $filePath = "{$modulePath}/{$folder}/{$target}.php";

        if (file_exists($filePath)) {
            return false;
        }

        $stubPath = self::resolveStubPath($stub);
        if ($stubPath === null) {
            return false;
        }

        $stubContent = strtr(file_get_contents($stubPath), [
            '{{ module }}' => self::getModulePath($module, $folder, $target),
            '{{ target }}' => self::getTarget($target),
            '{{ module_lower }}' => strtolower($module),
        ]);

        File::ensureDirectoryExists(dirname($filePath), 0755);
        file_put_contents($filePath, $stubContent);

        return true;
    }

    private static function modulePath(string $module): string
    {
        $base = config('modules.path') ?? app_path('Modules');

        return rtrim($base, '/') . "/{$module}";
    }

    private static function resolveStubPath(string $stub): ?string
    {
        $appStub = base_path("stubs/{$stub}");
        if (file_exists($appStub)) {
            return $appStub;
        }

        $packageStub = __DIR__ . '/../../stubs/' . $stub;
        if (file_exists($packageStub)) {
            return $packageStub;
        }

        return null;
    }

    private static function getTarget(string $target): string
    {
        $parts = explode('/', $target);

        return end($parts);
    }

    private static function getModulePath(string $module, string $folder, string $target): string
    {
        if ($folder === 'Routes') {
            return $module;
        }

        $parts = explode('/', $target);
        $path = "{$module}\\{$folder}";

        for ($i = 0; $i < count($parts) - 1; $i++) {
            $path .= "\\{$parts[$i]}";
        }

        return $path;
    }
}
