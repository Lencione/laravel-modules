<?php

it('creates the full folder structure for a new module', function () {
    $this->artisan('make:module', ['module' => 'Posts'])
        ->assertSuccessful();

    $expectedFolders = [
        'Actions', 'Controllers', 'Models', 'Requests', 'Resources',
        'Rules', 'Events', 'Listeners', 'Jobs', 'Routes', 'Services', 'Views',
    ];

    foreach ($expectedFolders as $folder) {
        expect(is_dir($this->modulePath("Posts/{$folder}")))->toBeTrue("Pasta {$folder} não foi criada");
    }
});

it('also generates the base files for a new module', function () {
    $this->artisan('make:module', ['module' => 'Posts'])->assertSuccessful();

    $expectedFiles = [
        'Controllers/PostsController.php',
        'Requests/StorePostsRequest.php',
        'Requests/UpdatePostsRequest.php',
        'Resources/PostsResource.php',
        'Models/Posts.php',
        'Services/PostsService.php',
        'Routes/web.php',
        'Routes/api.php',
    ];

    foreach ($expectedFiles as $file) {
        expect(file_exists($this->modulePath("Posts/{$file}")))
            ->toBeTrue("Arquivo {$file} não foi gerado");
    }
});

it('creates only the requested folder when one is passed', function () {
    $this->artisan('make:module', ['module' => 'Posts', 'folder' => 'Actions'])
        ->assertSuccessful();

    expect(is_dir($this->modulePath('Posts/Actions')))->toBeTrue();
    expect(is_dir($this->modulePath('Posts/Controllers')))->toBeFalse();
});

it('rejects invalid folder names', function () {
    $this->artisan('make:module', ['module' => 'Posts', 'folder' => 'NotAValidFolder'])
        ->assertFailed();

    expect(is_dir($this->modulePath('Posts/NotAValidFolder')))->toBeFalse();
});
