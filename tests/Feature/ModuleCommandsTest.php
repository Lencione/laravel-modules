<?php

beforeEach(function () {
    $this->artisan('make:module', ['module' => 'Posts'])->assertSuccessful();
});

it('module:action generates a class with correct namespace', function () {
    $this->artisan('module:action', ['module' => 'Posts', 'target' => 'PublishPost'])
        ->assertSuccessful();

    $file = $this->modulePath('Posts/Actions/PublishPost.php');
    expect(file_exists($file))->toBeTrue();
    expect(file_get_contents($file))->toContain('namespace App\Modules\Posts\Actions;');
    expect(file_get_contents($file))->toContain('class PublishPost');
});

it('module:controller defaults target to {Module}Controller', function () {
    @unlink($this->modulePath('Posts/Controllers/PostsController.php'));

    $this->artisan('module:controller', ['module' => 'Posts'])->assertSuccessful();

    expect(file_exists($this->modulePath('Posts/Controllers/PostsController.php')))->toBeTrue();
});

it('module:model defaults target to the module name without suffix', function () {
    @unlink($this->modulePath('Posts/Models/Posts.php'));

    $this->artisan('module:model', ['module' => 'Posts'])->assertSuccessful();

    expect(file_exists($this->modulePath('Posts/Models/Posts.php')))->toBeTrue();
});

it('module:service defaults target to {Module}Service', function () {
    @unlink($this->modulePath('Posts/Services/PostsService.php'));

    $this->artisan('module:service', ['module' => 'Posts'])->assertSuccessful();

    $file = $this->modulePath('Posts/Services/PostsService.php');
    expect(file_exists($file))->toBeTrue();
    expect(file_get_contents($file))->toContain('class PostsService extends BaseService');
});

it('supports subdirectories via slash in target', function () {
    $this->artisan('module:service', ['module' => 'Posts', 'target' => 'Auth/LoginService'])
        ->assertSuccessful();

    $file = $this->modulePath('Posts/Services/Auth/LoginService.php');
    expect(file_exists($file))->toBeTrue();
    expect(file_get_contents($file))->toContain('namespace App\Modules\Posts\Services\Auth;');
    expect(file_get_contents($file))->toContain('class LoginService');
});

it('refuses to overwrite an existing file', function () {
    $this->artisan('module:action', ['module' => 'Posts', 'target' => 'Once'])->assertSuccessful();
    $this->artisan('module:action', ['module' => 'Posts', 'target' => 'Once'])->assertFailed();
});

it('generates web.php and api.php with module:route', function () {
    @unlink($this->modulePath('Posts/Routes/web.php'));
    @unlink($this->modulePath('Posts/Routes/api.php'));

    $this->artisan('module:route', ['module' => 'Posts'])->assertSuccessful();

    expect(file_exists($this->modulePath('Posts/Routes/web.php')))->toBeTrue();
    expect(file_exists($this->modulePath('Posts/Routes/api.php')))->toBeTrue();
});
