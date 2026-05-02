<?php

namespace Lencione\LaravelModules\Tests;

use Illuminate\Filesystem\Filesystem;
use Lencione\LaravelModules\LaravelModulesServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected string $tempModulesPath;

    protected function setUp(): void
    {
        $this->tempModulesPath = sys_get_temp_dir() . '/laravel-modules-tests-' . uniqid('', true);
        if (! is_dir($this->tempModulesPath)) {
            mkdir($this->tempModulesPath, 0755, true);
        }

        parent::setUp();
    }

    protected function tearDown(): void
    {
        if (isset($this->tempModulesPath) && is_dir($this->tempModulesPath)) {
            (new Filesystem)->deleteDirectory($this->tempModulesPath);
        }

        parent::tearDown();
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('modules.path', $this->tempModulesPath);
    }

    protected function getPackageProviders($app): array
    {
        return [LaravelModulesServiceProvider::class];
    }

    protected function modulePath(string $module = ''): string
    {
        return rtrim($this->tempModulesPath . '/' . $module, '/');
    }
}
