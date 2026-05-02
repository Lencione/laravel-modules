<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lencione\LaravelModules\Tests\Fixtures\TestPost;
use Lencione\LaravelModules\Tests\Fixtures\TestPostService;

beforeEach(function () {
    config()->set('database.default', 'testing');
    config()->set('database.connections.testing', [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);

    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
    });
});

it('store creates a record', function () {
    $service = new TestPostService;

    $post = $service->store(['title' => 'Hello']);

    expect($post)->toBeInstanceOf(TestPost::class);
    expect($post->title)->toBe('Hello');
    expect(TestPost::count())->toBe(1);
});

it('getById returns the record when found', function () {
    $service = new TestPostService;
    $created = $service->store(['title' => 'Find me']);

    $found = $service->getById($created->id);

    expect($found->id)->toBe($created->id);
});

it('getById throws ModelNotFoundException when missing', function () {
    $service = new TestPostService;

    expect(fn () => $service->getById(999))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

it('getAll accepts a custom perPage', function () {
    $service = new TestPostService;
    foreach (range(1, 5) as $n) {
        $service->store(['title' => "post {$n}"]);
    }

    $page = $service->getAll(perPage: 2);

    expect($page->perPage())->toBe(2);
    expect($page->total())->toBe(5);
});

it('update changes attributes and returns the updated model', function () {
    $service = new TestPostService;
    $created = $service->store(['title' => 'Old']);

    $updated = $service->update($created->id, ['title' => 'New']);

    expect($updated->title)->toBe('New');
    expect(TestPost::find($created->id)->title)->toBe('New');
});

it('delete removes the record', function () {
    $service = new TestPostService;
    $created = $service->store(['title' => 'Bye']);

    $service->delete($created->id);

    expect(TestPost::find($created->id))->toBeNull();
});

it('getAll returns a paginator', function () {
    $service = new TestPostService;
    $service->store(['title' => 'a']);
    $service->store(['title' => 'b']);

    $page = $service->getAll();

    expect($page->total())->toBe(2);
});

it('getAllWithoutPagination returns a collection', function () {
    $service = new TestPostService;
    $service->store(['title' => 'a']);
    $service->store(['title' => 'b']);

    $all = $service->getAllWithoutPagination();

    expect($all)->toHaveCount(2);
});
