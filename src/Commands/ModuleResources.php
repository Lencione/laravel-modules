<?php

namespace Lencione\LaravelModules\Commands;

class ModuleResources extends AbstractMakeModuleCommand
{
    protected $signature = 'module:resource {module} {target?}';

    protected $description = 'Cria um resource para um módulo específico.';

    protected string $folder = 'Resources';

    protected string $stub = 'module-resource.stub';

    protected ?string $defaultSuffix = 'Resource';
}
