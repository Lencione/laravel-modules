<?php

namespace Lencione\LaravelModules\Tests\Fixtures;

use Lencione\LaravelModules\Services\BaseService;

class TestPostService extends BaseService
{
    public function __construct()
    {
        parent::__construct(new TestPost);
    }
}
