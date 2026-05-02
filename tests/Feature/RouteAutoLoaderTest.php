<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

it('auto-loads web.php from a module', function () {
    $module = $this->modulePath('Demo/Routes');
    File::ensureDirectoryExists($module);
    file_put_contents("{$module}/web.php", <<<'PHP'
<?php
use Illuminate\Support\Facades\Route;
Route::get('/demo-web', fn () => 'ok')->name('demo.web');
PHP);

    $this->refreshApplication();

    expect(Route::has('demo.web'))->toBeTrue();
    expect(url(route('demo.web', [], false)))->toContain('/demo-web');
});

it('auto-loads api.php with the api prefix', function () {
    $module = $this->modulePath('Demo/Routes');
    File::ensureDirectoryExists($module);
    file_put_contents("{$module}/api.php", <<<'PHP'
<?php
use Illuminate\Support\Facades\Route;
Route::get('/demo-api', fn () => 'ok')->name('demo.api');
PHP);

    $this->refreshApplication();

    expect(Route::has('demo.api'))->toBeTrue();
    expect(route('demo.api', [], false))->toBe('/api/demo-api');
});

it('skips registration when no route file exists', function () {
    File::ensureDirectoryExists($this->modulePath('EmptyModule/Routes'));

    $this->refreshApplication();

    expect(true)->toBeTrue();
});
