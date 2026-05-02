<?php

use Illuminate\Support\Facades\File;
use Lencione\LaravelModules\Helpers\Create;

beforeEach(function () {
    File::ensureDirectoryExists($this->modulePath('Posts/Services'));
});

it('substitutes {{ module }} {{ target }} {{ module_lower }} placeholders', function () {
    $written = Create::moduleFromStub('Services', 'module-service.stub', 'Posts', 'PostsService');

    expect($written)->toBeTrue();

    $contents = file_get_contents($this->modulePath('Posts/Services/PostsService.php'));
    expect($contents)
        ->toContain('namespace App\Modules\Posts\Services;')
        ->toContain('class PostsService extends BaseService');
});

it('returns false when the module folder does not exist', function () {
    $written = Create::moduleFromStub('Services', 'module-service.stub', 'DoesNotExist', 'Foo');

    expect($written)->toBeFalse();
});

it('returns false when the target file already exists', function () {
    Create::moduleFromStub('Services', 'module-service.stub', 'Posts', 'PostsService');
    $second = Create::moduleFromStub('Services', 'module-service.stub', 'Posts', 'PostsService');

    expect($second)->toBeFalse();
});

it('builds nested namespaces from a slashed target', function () {
    Create::moduleFromStub('Services', 'module-service.stub', 'Posts', 'Auth/LoginService');

    $contents = file_get_contents($this->modulePath('Posts/Services/Auth/LoginService.php'));
    expect($contents)
        ->toContain('namespace App\Modules\Posts\Services\Auth;')
        ->toContain('class LoginService');
});

it('uses the module name (not module/Routes) for the namespace inside route stubs', function () {
    File::ensureDirectoryExists($this->modulePath('Posts/Routes'));

    Create::moduleFromStub('Routes', 'module-route.stub', 'Posts', 'web');

    $contents = file_get_contents($this->modulePath('Posts/Routes/web.php'));
    expect($contents)->toContain("prefix('posts')");
});
